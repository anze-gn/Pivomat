<?php

require_once("model/PivoDB.php");
require_once("ViewHelper.php");
require_once("forms/PivoForm.php");

class PivoController {

    public static function index() {
        $rules = [
            "id" => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 1]
            ]
        ];

        $data = filter_input_array(INPUT_GET, $rules);

        if ($data["id"]) {
            echo ViewHelper::render("view/pivo-detail.php", [
                "pivo" => PivoDB::get($data)
            ]);
        } else {
            echo ViewHelper::render("view/pivo-list.php", [
                "title" => "seznam vseh piv",
                "piva" => PivoDB::getAll(array("aktiviran" => 1)),
                "neaktivnaPiva" => PivoDB::getAll(array("aktiviran" => 0))
            ]);
        }
    }

    public static function add() {
        $form = new PivoInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = PivoDB::insert($data);
            ViewHelper::redirect(BASE_URL . "piva?id=" . $id);
        } else {
            echo ViewHelper::render("view/pivo-form.php", [
                "title" => "Dodaj novo pivo",
                "form" => $form
            ]);
        }
    }

    public static function edit() {
        $editForm = new PivoEditForm("edit_form");
        $deleteForm = new PivoDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                PivoDB::update($data);
                ViewHelper::redirect(BASE_URL . "piva?id=" . $data["id"]);
            } else {
                echo ViewHelper::render("view/pivo-form.php", [
                    "title" => "Uredi podatke o pivu",
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
                $pivo = PivoDB::get($data);
                $dataSource = new HTML_QuickForm2_DataSource_Array($pivo);
                $editForm->addDataSource($dataSource);
                $deleteForm->addDataSource($dataSource);

                echo ViewHelper::render("view/pivo-form.php", [
                    "title" => "Uredi podatke o pivu",
                    "form" => $editForm,
                    "deleteForm" => $deleteForm
                ]);
            } else {
                throw new InvalidArgumentException("Pivo ne obstaja.");
            }
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
                $url = BASE_URL . "piva/edit?id=" . $data["id"];
            } else {
                $url = BASE_URL . "piva";
            }

            ViewHelper::redirect($url);
        }
    }

}
