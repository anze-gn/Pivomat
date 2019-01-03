<?php

class HtmlTemplates {

    public static function head($title) {
        ob_start();
        var_dump($_SESSION);
        $session = ob_get_clean();

        return '<!DOCTYPE html>
<html lang="sl">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="' . CSS_URL . 'style.css">
        <meta charset="UTF-8" />
        <title>' . $title . '</title>
    </head>
    <body>
    <div class="container">
    '. $session;
    }

    public static function foot() {
        return'
    </div>
    </body>
</html>';
    }


}