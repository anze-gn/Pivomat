<?php

require_once("model/ProdajalecDB.php");
require_once("model/AdminDB.php");
require_once("ViewHelper.php");
require_once("forms/ProdajalecForm.php");
require_once("forms/AdminForm.php");

class ProdajalciController {

    public static function index() {
        echo ViewHelper::render("view/prodajalec-list.php", [
            "title" => "seznam vseh prodajalcev",
            "prodajalci" => ProdajalecDB::getAll(array("aktiviran" => 1)),
            "neaktivniProdajalci" => ProdajalecDB::getAll(array("aktiviran" => 0))
        ]);
    }

    public static function get($id) {
        echo ViewHelper::render("view/prodajalec-detail.php", [
            "prodajalec" => ProdajalecDB::get(array('id' => $id))
        ]);
    }
    
    public static function add() {
        $form = new ProdajalecInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = ProdajalecDB::insert($data);
            ViewHelper::redirect(BASE_URL . "prodajalci/" . $id);
        } else {
            echo ViewHelper::render("view/prodajalec-form.php", [
                "title" => "Dodaj novega prodajalca",
                "form" => $form
            ]);
        }
    }
    
    public static function edit($id) {
        $editForm = new ProdajalecEditForm("edit_form");
        $deleteForm = new ProdajalecDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                ProdajalecDB::update($data);
                ViewHelper::redirect(BASE_URL . "prodajalci/" . $data["id"]);
            } else {
                echo ViewHelper::render("view/prodajalec-form.php", [
                    "title" => "Uredi podatke o prodajalcu",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            }
        } else {
            $prodajalec = ProdajalecDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($prodajalec);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            echo ViewHelper::render("view/prodajalec-form.php", [
                "title" => "Uredi podatke o prodajalcu",
                "form" => $editForm,
                "deleteForm" => $deleteForm
            ]);
        }
    }
    
    public static function admin() {
        echo ViewHelper::render("view/admin-detail.php", [
            "admin" => AdminDB::get(array('id' => 1))
        ]);
    }
    
    public static function editAdmin() {
        $editForm = new AdminEditForm("edit_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                AdminDB::update($data);
                ViewHelper::redirect(BASE_URL . "admin");
            } else {
                echo ViewHelper::render("view/prodajalec-form.php", [
                    "title" => "Uredi podatke o adminu",
                    "form" => $editForm
                ]);
            }
        } else {
            $prodajalec = AdminDB::get(array('id' => 1));
            $dataSource = new HTML_QuickForm2_DataSource_Array($prodajalec);
            $editForm->addDataSource($dataSource);

            echo ViewHelper::render("view/prodajalec-form.php", [
                "title" => "Uredi podatke o adminu",
                "form" => $editForm
            ]);
        }
    }

    public static function delete() {
        $form = new ProdajalecDeleteForm("delete_form");
        $data = $form->getValue();

        if ($form->isSubmitted() && $form->validate()) {
            ProdajalecDB::delete($data);
            ViewHelper::redirect(BASE_URL . "prodajalci");
        } else {
            if (isset($data["id"])) {
                $url = BASE_URL . "prodajalci/edit/" . $data["id"];
            } else {
                $url = BASE_URL . "prodajalci";
            }
            ViewHelper::redirect($url);
        }
    }
}
