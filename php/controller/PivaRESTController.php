<?php

require_once("model/PivoDB.php");
require_once("forms/PivoForm.php");

class PivaRESTController {

    public static function get($id) {
        try {
            $pivo = PivoDB::get(["id" => $id]);
            $pivo['slika'] = base64_encode(PivoDB::getSlika($id));
            echo ViewHelper::renderJSON($pivo);
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
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'prodajalci' || $_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
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
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'prodajalci' || $_SESSION['vloga'] == 'admin'))) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
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
        if (!(isset($_SESSION['vloga']) && $_SESSION['vloga'] == 'admin')) {
            echo Twig::instance()->render('accesss-denied.html');
            exit();
        }
        try {
            PivoDB::get(["id" => $id]);
            PivoDB::delete(["id" => $id]);
            echo ViewHelper::renderJSON("", 200);
        } catch (InvalidArgumentException $e) {
            echo ViewHelper::renderJSON($e->getMessage(), 404);
        }
    }

    public static function addToCart() {
        $cartItem = [];
        $fields = ['id', 'idPiva', 'kolicina', 'naziv', 'cena'];
        foreach ($fields as $item) {
            if (!isset($_POST[$item])) {
                echo ViewHelper::renderJSON("Napačni podatki.", 400);
                exit();
            }
            $cartItem[$item] = $_POST[$item];
        }
        $_SESSION['kosarica'][$cartItem['id']] = $cartItem;
        echo ViewHelper::renderJSON("Uspešno dodano v košarico.", 200);
    }

    public static function getCart() {
        echo ViewHelper::renderJSON($_SESSION['kosarica'], 200);
    }

    public static function removeFromCart($id) {
        unset($_SESSION['kosarica'][$id]);
        echo ViewHelper::renderJSON($_SESSION['kosarica'], 200);
    }

}
