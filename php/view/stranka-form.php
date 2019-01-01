<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "stranke" ?>">Seznam vseh strank</a> |
    <a href="<?= BASE_URL . "stranke/add" ?>">Dodaj novo stranko</a>
    ]</p>

<?= $form ?>

<?= isset($deleteForm) ? $deleteForm : "" ?>

<?= HtmlTemplates::foot(); ?>