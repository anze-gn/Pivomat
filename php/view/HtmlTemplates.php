<?php

class HtmlTemplates {

    public static function head($title) {
        echo('<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="' . CSS_URL . 'style.css">
        <meta charset="UTF-8" />
        <title>' . $title . '</title>
    </head>
    <body>');
    }

    public static function foot() {
        echo('
    </body>
</html>');
    }


}