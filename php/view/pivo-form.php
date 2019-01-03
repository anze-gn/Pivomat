<?php
    require_once("HtmlTemplates.php");
    require_once("custom_renderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "piva" ?>">Seznam vseh piv</a> |
    <a href="<?= BASE_URL . "piva/add" ?>">Dodaj novo</a>
    ]</p>

<?= $form->render($custom_renderer) ?>

<?= isset($deleteForm) ? $deleteForm->render($custom_renderer) : ""?>

<?= HtmlTemplates::foot(); ?>
