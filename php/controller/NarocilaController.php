<?php

require_once("model/NarociloDB.php");
require_once("model/PostavkaDB.php");
require_once("model/StrankaDB.php");
#require_once("forms/NarociloForm.php");

class NarocilaController
{
    public static $stanja = ['potrjeno', 'preklicano', 'stornirano', 'zakljuceno'];

    public static function index($filter) {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'admin' || $_SESSION['vloga'] == 'prodajalci'
            || ($filter == null && $_SESSION['vloga'] == 'stranke')))) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }
        if ($_SESSION['vloga'] == 'stranke') {
            $narocila = NarociloDB::getAll(['idStranka' => $_SESSION['uporabnik']['id']]);
            $subtitle = "Seznam vaših naročil";
        } elseif (in_array($filter, self::$stanja)) {
            $narocila = NarociloDB::getAll([$filter => null]);
            $tmp = str_replace("no", "nih", $filter);
            $subtitle = "Seznam $tmp naročil";
        } else {
            $narocila = NarociloDB::getAll();
            $subtitle = "Seznam vseh naročil";
        }
        for ($i = 0; $i < count($narocila); $i++) {
            $statusi = [];
            foreach (self::$stanja as $stanje) {
                if ($narocila[$i][$stanje]) {
                    $statusi[] = $stanje;
                }
            }
            $narocila[$i]['stanje'] = implode(", ", $statusi);
        }
        echo Twig::instance()->render('narocilo-list.html.twig', [
            "title" => "Seznam naročil",
            "subtitle" => $subtitle,
            "narocila" => $narocila
        ]);
    }

    public static function get($id) {
        $narocilo = NarociloDB::get(['id' => $id]);
        if (!(isset($_SESSION['vloga'])) || ($_SESSION['vloga'] == 'stranke' && $narocilo['idStranka'] != $_SESSION['uporabnik']['id'])) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }
        echo Twig::instance()->render('narocilo-detail.html.twig', [
            "title" => "Podrobnosti naročila",
            "postavke" => PostavkaDB::getAll(['idNarocilo' => $id]),
            "stranka" => StrankaDB::get(['id' => $narocilo['idStranka']]),
            "oddaj_narocilo" => false
        ]);
    }

    public static function potrdiNakup() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'stranke'))) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }

        if (!isset($_SESSION['kosarica'])) {
            echo Twig::instance()->render("error.html.twig", [
                "title" => "Košarica praznica.",
                "errorHtml" => '
                    <h1 class="my-3">Košarica je prazna</h1>
                    <h3 class="my-3">najprej dodajte artikle v košarico</h3>'
            ]);
            exit();
        }

        $kosarica = [];
        foreach ($_SESSION['kosarica'] as $cartItem) {
            $pivo = PivoDB::get(['id' => $cartItem['id']]);
            $cartItem = array_merge($cartItem, $pivo);
            $kosarica[] = $cartItem;
        }
        echo Twig::instance()->render('narocilo-detail.html.twig', [
            "title" => "Potrdi nakup",
            "postavke" => $kosarica,
            "stranka" => $_SESSION['uporabnik'],
            "oddaj_narocilo" => true
        ]);
        exit();
    }

    public static function narociloOddano() {
        if (!(isset($_SESSION['vloga']) && ($_SESSION['vloga'] == 'stranke'))) {
            echo Twig::instance()->render('access-denied.html.twig');
            exit();
        }

        if (!isset($_SESSION['kosarica'])) {
            echo Twig::instance()->render("error.html.twig", [
                "title" => "Košarica praznica.",
                "errorHtml" => '
                    <h1 class="my-3">Košarica je prazna</h1>
                    <h3 class="my-3">najprej dodajte artikle v košarico</h3>'
            ]);
            exit();
        }

        $idNarocila = NarociloDB::insert(['idStranka' => $_SESSION['uporabnik']['id']]);
        foreach ($_SESSION['kosarica'] as $cartItem) {
            $pivo = PivoDB::get(['id' => $cartItem['id']]);
            PostavkaDB::insert([
                'idArtikel' => $cartItem['id'],
                'kolicina' => $cartItem['kol'],
                'idNarocilo' => $idNarocila
            ]);
        }
        unset($_SESSION['kosarica']);
        echo Twig::instance()->render('narocilo-oddano.html.twig', ['idNarocila' => $idNarocila]);
        exit();
    }
}