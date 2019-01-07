<?php

use PHPMailer\PHPMailer\PHPMailer;
require_once("model/StrankaDB.php");
require_once("model/ProdajalecDB.php");
require_once("model/AdminDB.php");
require_once("forms/RegistracijaForm.php");
require_once("forms/PrijavaForm.php");

class PrijavaRegistracijaController {

    public static function registracija() {
        $form = new RegistracijaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            $form->captcha->clearCaptchaSession();
            $data["aktiviran"] = 0;
            $data["potrjen"] = password_hash($data["email"], PASSWORD_BCRYPT);
            unset($data["ponovitevGesla"]);
            $id = StrankaDB::insert($data);
            $link = BASE_URL ."potrditev?m=" . urlencode($data["email"]) . "&h=" . urlencode($data["potrjen"]);

            ViewHelper::redirect(BASE_URL . "potrditev");

            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = gethostbyname('smtp.gmail.com');
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPAuth = true;
            $mail->Username = "pivomat2019@gmail.com";
            $mail->Password = "craftpiva";
            $mail->setFrom('pivomat2019@gmail.com', 'Pivomat');
            $mail->addAddress($data['email']);
            $mail->Subject = "Potrditev računa Pivomat.";
            $mail->Body = "Za potrditev računa kliknite na spodnjo povezavo: \r\n" . "http://localhost".$link;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            if (!$mail->send()) {
                # Napaka pri pošiljanju, izbriši stranko, da lahko poskusi znova
                StrankaDB::delete(['id' => $id]);
            }
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Registriraj se",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        }
    }
    
    public static function prijava() {
        $form = new PrijavaForm("prijava");

        if ($form->validate()) {
            $data = $form->getValue();

            session_regenerate_id();

            $podatkiUporabnika = StrankaDB::getByEmail($data);
            if ($podatkiUporabnika && $podatkiUporabnika['potrjen'] == NULL && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                if ($podatkiUporabnika['aktiviran'] == 0) {
                    echo Twig::instance()->render("deactivated.html.twig");
                    exit();
                }
                $_SESSION["vloga"] = "stranke";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                ViewHelper::redirect(BASE_URL . "piva");
            }

            $podatkiUporabnika = ProdajalecDB::getByEmail($data);
            if ($podatkiUporabnika && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                if ($podatkiUporabnika['aktiviran'] == 0) {
                    echo Twig::instance()->render("deactivated.html.twig");
                    exit();
                }
                $_SESSION["vloga"] = "prodajalci";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                ViewHelper::redirect(BASE_URL . "stranke");
            }

            $podatkiUporabnika = AdminDB::getByEmail($data);
            if ($podatkiUporabnika && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                $_SESSION["vloga"] = "admin";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                ViewHelper::redirect(BASE_URL . "prodajalci");
            }

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Prijavi se",
                "error" => "Napačen e-mail ali geslo!",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Prijavi se",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function odjava() {
        session_destroy();
        ViewHelper::redirect(BASE_URL);
    }
    
    public static function potrdiEmail() {
        if(!isset($_GET['m']) && !isset($_GET['h'])){
            echo Twig::instance()->render("potrditev.html.twig", [
                "title" => "Potrdi registracijo"
            ]);
        } else if( isset($_GET['m']) && isset($_GET['h']) &&
                   $_GET['h'] == StrankaDB::getByEmail(["email" => $_GET['m']])['potrjen']){
            StrankaDB::potrdi(["email" => $_GET['m']]);
            ViewHelper::redirect(BASE_URL . "prijava");
        } else{
            echo Twig::instance()->render("error.html.twig", [
                "title" => "Napaka pri registraciji",
                "errorHtml" => '
                    <h1>Prišlo je do napake, poskusite znova.</h1>'
            ]);
        }
    }
}