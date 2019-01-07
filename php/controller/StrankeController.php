<?php

require_once("model/StrankaDB.php");
require_once("forms/StrankaForm.php");

class StrankeController {

    public static function index() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin') || $_SESSION['vloga'] == 'prodajalci')) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        echo Twig::instance()->render("stranka-list.html.twig", [
            "title" => "Seznam vseh strank",
            "stranke" => strankaDB::getAll(array("aktiviran" => 1)),
            "deaktiviraneStranke" => strankaDB::getAll(array("aktiviran" => 0))
        ]);
    }

    public static function get($id) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin' || $_SESSION['vloga'] == 'prodajalci'|| ($id == $_SESSION["uporabnik"]["id"] && $_SESSION['vloga'] == 'stranke')))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        echo Twig::instance()->render("stranka-detail.html.twig", [
            "title" => "Podatki stranke:",
            "stranka" => StrankaDB::get(array('id' => $id))
        ]);
    }

    public static function add() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin') || $_SESSION['vloga'] == 'prodajalci')) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        $form = new StrankaInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = StrankaDB::insert($data);
            ViewHelper::redirect(BASE_URL . "stranke/" . $id);
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Dodaj novo strank",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function edit($id) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin' || $_SESSION['vloga'] == 'prodajalci'|| ($id == $_SESSION["uporabnik"]["id"] && $_SESSION['vloga'] == 'stranke')))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        $editForm = new StrankaEditForm("edit_form");
        $deleteForm = new StrankaDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                StrankaDB::update($data);
                if ($_SESSION["vloga"] == "stranke" && $data['id'] == $_SESSION["uporabnik"]["id"]) {
                    $_SESSION["uporabnik"] = StrankaDB::get(["id" => $data['id']]);
                }
                ViewHelper::redirect(BASE_URL . "stranke/" . $data["id"]);
            } else {
                echo Twig::instance()->render("form.html.twig", [
                    "title" => "Uredi podatke o stranki",
                    "form" => (string) $editForm->render(CustomRenderer::instance()),
                    "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
                ]);
            }
        } else {
            $stranka = StrankaDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($stranka);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Uredi podatke o stranki",
                "form" => (string) $editForm->render(CustomRenderer::instance()),
                "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function delete() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
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