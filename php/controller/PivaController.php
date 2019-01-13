<?php

require_once("model/PivoDB.php");
require_once("forms/PivoForm.php");

class PivaController {

    public static function index() {
        echo Twig::instance()->render('pivo-list.html.twig', [
            "title" => "Seznam piv",
            "piva" => PivoDB::getAll(["aktiviran" => 1])
        ]);
    }

    public static function indexDeaktivirana() {
        echo Twig::instance()->render('pivo-list.html.twig', [
            "title" => "Seznam deaktiviranih piv",
            "piva" => PivoDB::getAll(["aktiviran" => 0])
        ]);
    }

    public static function get($id) {
        echo Twig::instance()->render('pivo-detail.html.twig', [
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
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'prodajalci' || $_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }
        $form = new PivoInsertForm("add_form");

        if ($form->validate()) {
            $data = $form->getValue();
            if(!isset($data['aktiviran'])){
                    $data['aktiviran'] = "0";
            }
            if ($data['slika']['size'] < 1) {
                unset($data['slika']);
            }
            $id = PivoDB::insert($data);
            ViewHelper::redirect(BASE_URL . "piva/" . $id);
        } else {
            echo Twig::instance()->render("form.html.twig", [
                "title" => "Dodaj novo pivo",
                "form" => (string) $form->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function edit($id) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'prodajalci' || $_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }
        $editForm = new PivoEditForm("edit_form");
        $editForm->obstojecaSlika->setAttribute('src', "../" . $id . ".jpg");
        $deleteForm = new PivoDeleteForm("delete_form");

        if ($editForm->isSubmitted()) {
            if ($editForm->validate()) {
                $data = $editForm->getValue();
                if(!isset($data['aktiviran'])){
                    $data['aktiviran'] = "0";
                }
                if ($data['slika']['size'] < 1) {
                    unset($data['slika']);
                }
                PivoDB::update($data);
                ViewHelper::redirect(BASE_URL . "piva/" . $data["id"]);
            } else {
                echo Twig::instance()->render("form.html.twig", [
                    "title" => "Uredi podatke o pivu",
                    "form" => (string) $editForm->render(CustomRenderer::instance()),
                    "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
                ]);
            }
        } else {
            $pivo = PivoDB::get(array('id' => $id));
            $dataSource = new HTML_QuickForm2_DataSource_Array($pivo);
            $editForm->addDataSource($dataSource);
            $deleteForm->addDataSource($dataSource);

            echo Twig::instance()->render("form.html.twig", [
                "title" => "Uredi podatke o pivu",
                "form" => (string) $editForm->render(CustomRenderer::instance()),
                "deleteForm" => (string) $deleteForm->render(CustomRenderer::instance())
            ]);
        }
    }

    public static function delete() {
        if (!(isset($_SESSION['vloga']) && $_SESSION['vloga'] == 'admin')) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }
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
