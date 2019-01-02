<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "prijava" ?>">Prijavi se</a>
    ]</p>

<?= $form ?>

<?= HtmlTemplates::foot(); ?>