<?php

require_once("model/StrankaDB.php");
require_once("forms/StrankaForm.php");

class StrankeController {

    public static function index() {
        echo ViewHelper::render("view/stranka-list.php", [
            "title" => "seznam vseh strank",
            "stranke" => strankaDB::getAll(array("aktiviran" => 1)),
            "neaktivneStranke" => strankaDB::getAll(array("aktiviran" => 0))
        ]);
    }

    public static function get($id) {
        echo ViewHelper::render("view/stranka-detail.php", [
            "stranka" => StrankaDB::get(array('id' => $id))
        ]);
    }

    public static function add() {
        $form = new StrankaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = StrankaDB::insert($data);
            ViewHelper::redirect(BASE_URL . "stranke/" . $id);
        } else {
            echo ViewHelper::render("view/stranka-form.php", [
                "title" => "Dodaj novo stranko",
                "form" => $form
            ]);
        }
    }

    public static function edit($id) {
        $editForm = new StrankaEditForm("edit_form");
        $deleteForm = new StrankaDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                StrankaDB::update($data);
                ViewHelper::redirect(BASE_URL . "stranke/" . $data["id"]);
            } else {
                echo ViewHelper::render("view/stranka-form.php", [
                    "title" => "Uredi podatke o stranki",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            }
        } else {
            $stranka = StrankaDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($stranka);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            echo ViewHelper::render("view/stranka-form.php", [
                "title" => "Uredi podatke o stranki",
                "form" => $editForm,
                "deleteForm" => $deleteForm
            ]);
        }
    }

    public static function delete() {
        $form = new PivoDeleteForm("delete_form");
        $data = $form->getValue();

        if ($form->isSubmitted() && $form->validate()) {
            StrankaDB::delete($data);
            ViewHelper::redirect(BASE_URL . "stranke");
        } else {
            if (isset($data["id"])) {
                $url = BASE_URL . "stranke/edit/" . $data["id"];
            } else {
                $url = BASE_URL . "stranke";
            }

            ViewHelper::redirect($url);
        }
    }

}