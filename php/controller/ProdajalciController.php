<?php

require_once("model/ProdajalecDB.php");
require_once("ViewHelper.php");
require_once("forms/ProdajalecForm.php");

class ProdajalciController {

    public static function index() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);

        if ($data["id"]) {
            echo ViewHelper::render("view/prodajalec-detail.php", [
                "prodajalec" => ProdajalecDB::get($data)
            ]);
        } else {
            /*echo ViewHelper::render("view/prodajalec-list.php", [
                "title" => "seznam vseh prodajalcev",
                "prodajalci" => ProdajalecDB::getAll()
            ]);*/
            $data["aktiviran"] = 1;
            $data2["aktiviran"] = 0;
            echo ViewHelper::render("view/prodajalec-list.php", [
                "title" => "seznam vseh prodajalcev",
                "prodajalci" => ProdajalecDB::getAllActivity($data),
                "neaktivniProdajalci" => ProdajalecDB::getAllActivity($data2)
            ]);
        }
    }
    
    public static function add() {
        $form = new ProdajalecInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = ProdajalecDB::insert($data);
            ViewHelper::redirect(BASE_URL . "prodajalci?id=" . $id);
        } else {
            echo ViewHelper::render("view/prodajalec-form.php", [
                "title" => "Dodaj novega prodajalca",
                "form" => $form
            ]);
        }
    }
    
    public static function edit() {
        $editForm = new ProdajalecEditForm("edit_form");
        $deleteForm = new ProdajalecDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                ProdajalecDB::update($data);
                ViewHelper::redirect(BASE_URL . "prodajalci?id=" . $data["id"]);
            } else {
                echo ViewHelper::render("view/prodajalec-form.php", [
                    "title" => "Uredi podatke o prodajalcu",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            }
        } else {
            $rules = [
                "id" => [
                    'filter' => FILTER_VALIDATE_INT,
                    'options' => ['min_range' => 1]
                ]
            ];

            $data = filter_input_array(INPUT_GET, $rules);

            if ($data["id"]) {
                $prodajalec = ProdajalecDB::get($data);
                $dataSource = new HTML_QuickForm2_DataSource_Array($prodajalec);
                $editForm->addDataSource($dataSource);
                $deleteForm->addDataSource($dataSource);

                echo ViewHelper::render("view/prodajalec-form.php", [
                    "title" => "Uredi podatke o prodajalcu",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            } else {
                throw new InvalidArgumentException("Prodajalec ne obstaja.");
            }
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
                $url = BASE_URL . "prodajalci/edit?id=" . $data["id"];
            } else {
                $url = BASE_URL . "prodajalci";
            }
            ViewHelper::redirect($url);
        }
    }
}