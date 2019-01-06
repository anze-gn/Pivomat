<?php
    require_once("HtmlTemplates.php");
    require_once("forms/CustomRenderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "prijava" ?>">Prijavi se</a>
    ]</p>

<?= $form->render(CustomRenderer::instance()) ?>

<?= HtmlTemplates::foot(); ?>