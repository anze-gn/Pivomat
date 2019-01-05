<?php

require_once("model/StrankaDB.php");
require_once("model/ProdajalecDB.php");
require_once("model/AdminDB.php");
require_once("ViewHelper.php");
require_once("forms/RegistracijaForm.php");
require_once("forms/PrijavaForm.php");

class PrijavaRegistracijaController {

    public static function registracija() {
        $form = new RegistracijaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            $form->captcha->clearCaptchaSession();
            $data["aktiviran"] = 0;
            unset($data["ponovitevGesla"]);
            $id = StrankaDB::insert($data);
            /*$link = rtrim($_SERVER["SCRIPT_NAME"], "/index.php") . trim($_SERVER["PATH_INFO"]) ."/potrditev/id/" . $id;
            $subject = "Potrditev računa Pivomat.";
            $content = "Klikni povezavo za potrditev: <a href='" . $link . "'>" . "Potrdi" . "</a>";
            $content = wordwrap($content, 70, "\r\n");
            $mailHeaders = "From: Admin\r\n";
            mail($data["email"], $subject, $content, $mailHeaders);*/
            ViewHelper::redirect(BASE_URL . "prijava");
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
            if(password_verify($data["geslo"], StrankaDB::getPasswordHash($data["email"]))){
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
}