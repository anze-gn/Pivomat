<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "prodajalci" ?>">Seznam vseh prodajalcev</a> |
    <a href="<?= BASE_URL . "prodajalci/add" ?>">Dodaj novega</a>
    ]</p>

<?= $form ?>

<?= isset($deleteForm) ? $deleteForm : "" ?>

<?= HtmlTemplates::foot(); ?>