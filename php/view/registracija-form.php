<?php
    require_once("HtmlTemplates.php");
    require_once("custom_renderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "prijava" ?>">Prijavi se</a>
    ]</p>

<?= $form->render($custom_renderer) ?>

<?= HtmlTemplates::foot(); ?>