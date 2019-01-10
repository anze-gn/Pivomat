<?php

class ViewHelper {

    // Preusmeritev na $url.
    public static function redirect($url) {
        header("Location: " . $url);
        header("Connection: close", true);
        header("Content-Encoding: none\r\n");
        header("Content-Length: 0", true);
        flush();
        ob_flush();
        session_write_close();
    }

    // Prikaz ob napaki 404.
    public static function error404() {
        header('Stran ne obstaja.', true, 404);
        echo Twig::instance()->render("error.html.twig", [
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
            echo Twig::instance()->render("error.html.twig", [
                "title" => "Napaka na strežniku.",
                "errorHtml" => '
                    <h1>Prišlo je do napake</h1>
                    <p><b>Podrobnosti:</b></p><pre>' . $exception . '</pre>'
            ]);
        } else {
            echo Twig::instance()->render("error.html.twig", [
                "title" => "Napaka na strežniku.",
                "errorHtml" => '
                    <h1>Prišlo je do napake</h1>'
            ]);
        }
    }

    public static function renderJSON($data, $httpResponseCode = 200) {
        header('Content-Type: application/json');
        http_response_code($httpResponseCode);
        return json_encode($data);
    }

    public static function renderJpeg($data) {
        header('Content-Type: image/jpeg');
        echo($data);
    }

    public static function forceHttps() {
        if (!isset($_SERVER["HTTPS"])) {
            $url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            header("Location: " . $url);
        }
    }

}
