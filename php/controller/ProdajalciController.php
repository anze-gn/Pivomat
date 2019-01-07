<?php

require_once("model/ProdajalecDB.php");
require_once("model/AdminDB.php");
require_once("forms/ProdajalecForm.php");
require_once("forms/AdminForm.php");

class ProdajalciController {

    public static function index() {
        echo Twig::instance()->render("prodajalec-list.html.twig", [
            "title" => "Seznam vseh prodajalcev",
            "prodajalci" => ProdajalecDB::getAll(array("aktiviran" => 1)),
            "deaktiviraniProdajalci" => ProdajalecDB::getAll(array("aktiviran" => 0))
        ]);
    }

    public static function get($id) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin' || $id == $_SESSION["uporabnik"]["id"]))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        echo Twig::instance()->render("prodajalec-detail.html.twig", [
            "title" => "Podatki prodajalca",
            "prodajalec" => ProdajalecDB::get(array('id' => $id)),
            "vloga" => "prodajalci"
        ]);
    }
    
    public static function add() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        $form = new ProdajalecInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!array_key_exists('aktiviran', $data)){
                $data["aktiviran"] = 0;
            }
            $id = ProdajalecDB::insert($data);
            ViewHelper::redirect(BASE_URL . "prodajalci/" . $id);
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Dodaj novega prodajalca",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        }
    }
    
    public static function edit($id) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin' || $id == $_SESSION["uporabnik"]["id"]))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        $editForm = new ProdajalecEditForm("edit_form");
        $deleteForm = new ProdajalecDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!array_key_exists('aktiviran', $data)){
                    $data["aktiviran"] = 0;
                }
                ProdajalecDB::update($data);
                if ($_SESSION["vloga"] == "prodajalci" && $data['id'] == $_SESSION["uporabnik"]["id"]) {
                    $_SESSION["uporabnik"] = ProdajalecDB::get(["id" => $data['id']]);
                }
                ViewHelper::redirect(BASE_URL . "prodajalci/" . $data["id"]);
            } else {
                echo Twig::instance()->render("form.html.twig", [
                    "title" => "Uredi podatke o prodajalcu",
                    "form" => (string) $editForm->render(CustomRenderer::instance()),
                    "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
                ]);
            }
        } else {
            $prodajalec = ProdajalecDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($prodajalec);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Uredi podatke o prodajalcu",
                "form" => (string) $editForm->render(CustomRenderer::instance()),
                "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
            ]);
        }
    }
    
    public static function admin() {
        echo Twig::instance()->render("prodajalec-detail.html.twig", [
            "title" => "Vaši podatki",
            "prodajalec" => AdminDB::get(array('id' => 1)),
            "vloga" => "admin"
        ]);
    }
    
    public static function editAdmin() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        $editForm = new AdminEditForm("edit_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                AdminDB::update($data);
                $_SESSION["uporabnik"] = AdminDB::get(["id" => $data['id']]);
                ViewHelper::redirect(BASE_URL . "admin");
            } else {
                echo Twig::instance()->render("form.html.twig", [
                    "title" => "Uredi podatke o adminu",
                    "form" => (string) $editForm->render(CustomRenderer::instance())
                ]);
            }
        } else {
            $prodajalec = AdminDB::get(array('id' => 1));
            $dataSource = new HTML_QuickForm2_DataSource_Array($prodajalec);
            $editForm->addDataSource($dataSource);

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Uredi podatke o adminu",
                "form" => (string) $editForm->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function delete() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
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
