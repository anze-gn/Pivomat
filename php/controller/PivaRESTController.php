<?php

require_once("model/PivoDB.php");
require_once("forms/PivoForm.php");
require_once("forms/PrijavaForm.php");

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
            echo Twig::instance()->render('accesss-denied.html.twig');
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
            echo Twig::instance()->render('accesss-denied.html.twig');
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
            echo Twig::instance()->render('accesss-denied.html.twig');
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
        $fields = ['idPiva', 'kolicina', 'naziv', 'cena'];
        foreach ($fields as $item) {
            if (!isset($_POST[$item])) {
                echo ViewHelper::renderJSON("Napačni podatki.", 400);
                exit();
            }
            $cartItem[$item] = $_POST[$item];
        }
        if (!isset($_SESSION['kosarica'])) {
            $_SESSION['kosarica'] = [];
        }
        $cartItem['id'] = 0;
        foreach ($_SESSION['kosarica'] as $item) {
            if ($item['id'] > $cartItem['id']) {
                $cartItem['id'] = $item['id'];
            }
        }
        $cartItem['id']++;
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

    public static function login() {
        # POST zahtevek more vsebovati parameter '_qf__prijava' = ''
        $form = new PrijavaForm("prijava");

        if ($form->validate()) {
            $data = $form->getValue();

            session_regenerate_id();

            $podatkiUporabnika = StrankaDB::getByEmail($data);
            if ($podatkiUporabnika && $podatkiUporabnika['potrjen'] == NULL && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                if ($podatkiUporabnika['aktiviran'] == 0) {
                    echo ViewHelper::renderJSON("uporabnik deaktiviran", 401);
                    exit();
                }
                $_SESSION["vloga"] = "stranke";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                echo ViewHelper::renderJSON("Prijava uspešna.", 200);
                exit();
            }

            $podatkiUporabnika = ProdajalecDB::getByEmail($data);
            if ($podatkiUporabnika && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                if ($podatkiUporabnika['aktiviran'] == 0) {
                    echo ViewHelper::renderJSON("uporabnik deaktiviran", 401);
                    exit();
                }
                $_SESSION["vloga"] = "prodajalci";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                echo ViewHelper::renderJSON("Prijava uspešna.", 200);
                exit();
            }

            $podatkiUporabnika = AdminDB::getByEmail($data);
            if ($podatkiUporabnika && password_verify($data["geslo"], $podatkiUporabnika['geslo'])) {
                $_SESSION["vloga"] = "admin";
                $_SESSION["uporabnik"] = $podatkiUporabnika;
                echo ViewHelper::renderJSON("Prijava uspešna.", 200);
                exit();
            }

            echo ViewHelper::renderJSON("Napačen e-mail ali geslo!", 401);
            exit();
        } else {
            echo ViewHelper::renderJSON("Ni podatkov.", 400);
            exit();
        }
    }

    public static function logout() {
        session_destroy();
        echo ViewHelper::renderJSON("Odjava uspešna.", 200);
        exit();
    }

}
