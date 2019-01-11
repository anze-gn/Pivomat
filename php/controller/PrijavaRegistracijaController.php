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
            exit();
        }
    }
    
    public static function prijava($vloga) {

        $form = new PrijavaForm("prijava");

        if ($vloga == "admin" || $vloga == "prodajalci") {
            $client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");

            if ($client_cert == null) {
                header('Napaka na strezniku.', true, 400);
                echo Twig::instance()->render("error.html.twig", [
                    "title" => "Prijava s certifikatom.",
                    "errorHtml" => '<h1>Prijava je možna samo z veljavnim certifikatom</h1>'
                ]);
                exit();
            }

            $cert_data = openssl_x509_parse($client_cert);
            $certEmailAddress = (is_array($cert_data['subject']['emailAddress']) ?
                $cert_data['subject']['emailAddress'][0] : $cert_data['subject']['emailAddress']);
            $dataSource = new HTML_QuickForm2_DataSource_Array(['email' => $certEmailAddress]);
            $form->addDataSource($dataSource);
        }

        if ($form->validate()) {
            $data = $form->getValue();

            if ($vloga == 'admin') {
                $podatkiUporabnika = AdminDB::getByEmail(['email' => $certEmailAddress]);
                $podatkiUporabnika['aktiviran'] = 1;
                $podatkiUporabnika['potrjen'] = NULL;
            } elseif ($vloga == 'prodajalci') {
                $podatkiUporabnika = ProdajalecDB::getByEmail(['email' => $certEmailAddress]);
                $podatkiUporabnika['potrjen'] = NULL;
            } else {
                $podatkiUporabnika = StrankaDB::getByEmail($data);
            }

            if ($podatkiUporabnika && $podatkiUporabnika['potrjen'] == NULL && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                if ($podatkiUporabnika['aktiviran'] == 0) {
                    echo Twig::instance()->render("deactivated.html.twig");
                    exit();
                }
                $_SESSION["vloga"] = ($vloga) ? $vloga : 'stranke';
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                session_regenerate_id();
                echo Twig::instance()->render("prijava-uspesna.html.twig");
                exit();
            }

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Prijavi se",
                "error" => "Napačen e-mail ali geslo!",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
            exit();
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Prijavi se",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
            exit();
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
            exit();
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
            exit();
        }
    }
}