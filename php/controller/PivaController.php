<?php

require_once("model/PivoDB.php");
require_once("ViewHelper.php");
require_once("forms/PivoForm.php");

class PivaController {

    public static function index() {
        echo ViewHelper::render("view/pivo-list.php", [
            "title" => "seznam vseh piv",
            "piva" => PivoDB::getAll(array("aktiviran" => 1)),
            "neaktivnaPiva" => PivoDB::getAll(array("aktiviran" => 0))
        ]);
    }

    public static function get($id) {
        echo ViewHelper::render("view/pivo-detail.php", [
            "pivo" => PivoDB::get(array('id' => $id))
        ]);
    }

    public static function getSlika($id) {
        $slika = PivoDB::getSlika($id);
        if (ViewHelper::renderJpeg($slika) != null) {
            ViewHelper::renderJpeg($slika);
        }
        echo ViewHelper::error404();
    }

    public static function add() {
        $form = new PivoInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = PivoDB::insert($data);
            ViewHelper::redirect(BASE_URL . "piva/" . $id);
        } else {
            echo ViewHelper::render("view/pivo-form.php", [
                "title" => "Dodaj novo pivo",
                "form" => $form
            ]);
        }
    }

    public static function edit($id) {
        $editForm = new PivoEditForm("edit_form");
        $deleteForm = new PivoDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                PivoDB::update($data);
                ViewHelper::redirect(BASE_URL . "piva/" . $data["id"]);
            } else {
                echo ViewHelper::render("view/pivo-form.php", [
                    "title" => "Uredi podatke o pivu",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            }
        } else {
            $pivo = PivoDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($pivo);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            $editForm->obstojecaSlika->setAttribute('src', "../" . $id . ".jpg");

            echo ViewHelper::render("view/pivo-form.php", [
                "title" => "Uredi podatke o pivu",
                "form" => $editForm,
                "deleteForm" => $deleteForm
            ]);
        }
    }

    public static function delete() {
        $form = new PivoDeleteForm("delete_form");
        $data = $form->getValue();

        if ($form->isSubmitted() && $form->validate()) {
            PivoDB::delete($data);
            ViewHelper::redirect(BASE_URL . "piva");
        } else {
            if (isset($data["id"])) {
                $url = BASE_URL . "piva/edit/" . $data["id"];
            } else {
                $url = BASE_URL . "piva";
            }

            ViewHelper::redirect($url);
        }
    }

}
