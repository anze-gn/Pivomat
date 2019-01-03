<?php
    require_once("HtmlTemplates.php");
    require_once("custom_renderer.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "registracija" ?>">Registriraj se</a>
    ]</p>
<h4>
    <?= isset($error) ? $error : "" ?>
</h4>

<?= $form->render($custom_renderer) ?>

<?= HtmlTemplates::foot(); ?>