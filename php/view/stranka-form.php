<?php
    require_once("HtmlTemplates.php");
    require_once("forms/CustomRenderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "stranke" ?>">Seznam vseh strank</a> |
    <a href="<?= BASE_URL . "stranke/add" ?>">Dodaj novo stranko</a>
    ]</p>

<?= $form->render(CustomRenderer::instance()) ?>

<?= isset($deleteForm) ? $deleteForm->render(CustomRenderer::instance()) : ""?>

<?= HtmlTemplates::foot(); ?>