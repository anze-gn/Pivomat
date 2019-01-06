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
            echo ViewHelper::render("view/registracija-form.php", [
                "title" => "Registriraj se",
                "form" => $form
            ]);
        }
    }
    
    public static function prijava() {
        $form = new PrijavaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            $podatkiStranke = StrankaDB::getPasswordHash($data["email"]);
            if(password_verify($data["geslo"], $podatkiStranke[0]) && $podatkiStranke[1] == NULL){
                $_SESSION["uporabnik"] = "stranka";
                ViewHelper::redirect(BASE_URL . "piva");
            } else if(password_verify($data["geslo"], ProdajalecDB::getPasswordHash($data["email"]))){
                $_SESSION["uporabnik"] = "prodajalec";
                ViewHelper::redirect(BASE_URL . "stranke");
            } else if(password_verify($data["geslo"], AdminDB::getPasswordHash($data["email"]))){
                $_SESSION["uporabnik"] = "admin";
                ViewHelper::redirect(BASE_URL . "prodajalci");
            }
            echo ViewHelper::render("view/prijava-form.php", [
                    "title" => "Prijavi se",
                    "error" => "Napačen e-mail ali geslo!",
                    "form" => $form
            ]);
        } else {
            echo ViewHelper::render("view/prijava-form.php", [
                "title" => "Prijavi se",
                "form" => $form
            ]);
        }
    }
    
    public static function potrdiEmail($email, $hash) {
        if($email == ""){
            echo ViewHelper::render("view/potrditev.php", [
                "title" => "Potrdi registracijo"
            ]);
        } else if($hash == StrankaDB::getPasswordHash($email)[1]){
            StrankaDB::potrdi(array("email" => $email));
            ViewHelper::redirect(BASE_URL . "prijava");
        } else{
            $a = StrankaDB::getPasswordHash($email)[1];
            echo ViewHelper::render("view/potrditev.php", [
                "title" => "Napaka pri registraciji",
                "error" => "Prišlo je do napake, poskusite znova."
            ]);
        }
    }
}