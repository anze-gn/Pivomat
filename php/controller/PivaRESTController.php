<?php

require_once("model/PivoDB.php");
require_once("ViewHelper.php");
require_once("forms/PivoForm.php");

class PivaRESTController {

    public static function get($id) {
        try {
            echo ViewHelper::renderJSON(PivoDB::get(["id" => $id]));
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }

    public static function index() {
        $prefix = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]
            . $_SERVER["REQUEST_URI"] . "/";
        echo ViewHelper::renderJSON(PivoDB::getAllwithURI(["prefix" => $prefix, "aktiviran" => 1]));
    }

    public static function add() {
        $form = new PivoInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = PivoDB::insert($data);
            ViewHelper::redirect(BASE_URL . "api/piva/$id");
        } else {
            echo ViewHelper::renderJSON("Napačni podatki.", 400);
        }
    }

    public static function edit($id) {
        $editForm = new PivoEditForm("edit_form");

        if ($editForm->validate()) {
            $data = $editForm->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            PivoDB::update($data);
            echo ViewHelper::renderJSON("", 200);
        } else {
            echo ViewHelper::renderJSON("Napačni podatki.", 400);
        }
    }

    public static function delete($id) {
        try {
            PivoDB::get(["id" => $id]);
            PivoDB::delete(["id" => $id]);
            echo ViewHelper::renderJSON("", 200);
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }

}
