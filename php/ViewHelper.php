<?php

class ViewHelper {

    // Pikaže podani view - $file
    // in v scope doda $variables array.
    public static function render($file, $variables = array()) {
        extract($variables);

        ob_start();
        include($file);
        return ob_get_clean();
    }

    // Preusmeritev na $url.
    public static function redirect($url) {
        header("Location: " . $url);
    }

    // Prikaz ob napaki 404.
    public static function error404() {
        header('Stran ne obstaja.', true, 404);
        echo self::render("view/error.php", [
            "title" => "Stran ne obstaja.",
            "errorHtml" => '
    <h1>Napaka 404: Stran ne obstaja</h1>
    <p>Stran na naslovu <b>' . $_SERVER["REQUEST_URI"] . '</b> ne obstaja.</p>'
        ]);
    }

    // Prikaz ostalih napak.
    public static function displayError($exception, $debug = false) {
        header('Napaka na strezniku.', true, 500);

        if ($debug) {
            echo self::render("view/error.php", [
                "title" => "Napaka na strežniku.",
                "errorHtml" => '
    <h1>Prišlo je do napake</h1>
    <p><b>Podrobnosti:</b></p><pre>' . $exception . '</pre>'
            ]);
        } else {
            echo self::render("view/error.php", [
                "title" => "Napaka na strežniku.",
                "errorHtml" => '
    <h1>Prišlo je do napake</h1>'
            ]);
        }
    }

}
