<?php

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
            $link = BASE_URL ."potrditev/" . $data["email"] . "/" . $data["potrjen"];
            $subject = "Potrditev računa Pivomat.";
            $content = "Za potrditev računa kliknite na spodnjo povezavo: \r\n" . $link;
            $header="from: Pivomat <no-reply@pivomat.si>";
            ViewHelper::redirect(BASE_URL . "potrditev");
            mail($data["email"], $subject, $content, $header);
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
                $_SESSION["vloga"] = "stranke";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                ViewHelper::redirect(BASE_URL . "piva");
            }

            $podatkiUporabnika = ProdajalecDB::getByEmail($data);
            if ($podatkiUporabnika && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
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
    
    public static function potrdiEmail($email, $hash) {
        if($email == ""){
            echo Twig::instance()->render("potrditev.html.twig", [
                "title" => "Potrdi registracijo"
            ]);
        } else if($hash == StrankaDB::getPasswordHash($email)[1]){
            StrankaDB::potrdi(array("email" => $email));
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