<?php
    require_once("HtmlTemplates.php");
    require_once("custom_renderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "prodajalci" ?>">Seznam vseh prodajalcev</a> |
    <a href="<?= BASE_URL . "prodajalci/add" ?>">Dodaj novega prodajalca</a>
    ]</p>

<?= $form->render($custom_renderer) ?>

<?= isset($deleteForm) ? $deleteForm->render($custom_renderer) : ""?>

<?= HtmlTemplates::foot(); ?>