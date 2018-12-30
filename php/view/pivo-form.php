<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "piva" ?>">Seznam vseh piv</a> |
    <a href="<?= BASE_URL . "piva/add" ?>">Dodaj novo</a>
    ]</p>

<?= $form ?>

<?= isset($deleteForm) ? $deleteForm : "" ?>

<?= HtmlTemplates::foot(); ?>
