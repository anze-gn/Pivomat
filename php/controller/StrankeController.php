<?php

require_once("model/StrankaDB.php");
require_once("ViewHelper.php");
require_once("forms/StrankaForm.php");

class StrankeController {

    public static function index() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);

        if ($data["id"]) {
            echo ViewHelper::render("view/stranka-detail.php", [
                "stranka" => StrankaDB::get2($data)
            ]);
        } else {
            /*echo ViewHelper::render("view/stranka-list.php", [
                "title" => "seznam vseh strank",
                "stranke" => StrankaDB::getAll()
            ]);*/
            $data["aktiviran"] = 1;
            $data2["aktiviran"] = 0;
            echo ViewHelper::render("view/stranka-list.php", [
                "title" => "seznam vseh strank",
                "stranke" => strankaDB::getAllActivity($data),
                "neaktivneStranke" => strankaDB::getAllActivity($data2)
            ]);
        }
    }

    public static function add() {
        $form = new StrankaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = StrankaDB::insert($data);
            ViewHelper::redirect(BASE_URL . "stranke?id=" . $id);
        } else {
            echo ViewHelper::render("view/stranka-form.php", [
                "title" => "Dodaj novo stranko",
                "form" => $form
            ]);
        }
    }

    public static function edit() {
        $editForm = new StrankaEditForm("edit_form");
        $deleteForm = new StrankaDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                StrankaDB::update($data);
                ViewHelper::redirect(BASE_URL . "stranke?id=" . $data["id"]);
            } else {
                echo ViewHelper::render("view/stranka-form.php", [
                    "title" => "Uredi podatke o stranki",
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
                $stranka = StrankaDB::get($data);
                $dataSource = new HTML_QuickForm2_DataSource_Array($stranka);
                $editForm->addDataSource($dataSource);
                $deleteForm->addDataSource($dataSource);

                echo ViewHelper::render("view/stranka-form.php", [
                    "title" => "Uredi podatke o stranki",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            } else {
                throw new InvalidArgumentException("Stranka ne obstaja.");
            }
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
                $url = BASE_URL . "stranke/edit?id=" . $data["id"];
            } else {
                $url = BASE_URL . "stranke";
            }

            ViewHelper::redirect($url);
        }
    }

}