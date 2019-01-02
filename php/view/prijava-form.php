<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<p>[
    <a href="<?= BASE_URL . "registracija" ?>">Registriraj se</a>
    ]</p>
<h4>
    <?= isset($error) ? $error : "" ?>
</h4>

<?= $form ?>

<?= HtmlTemplates::foot(); ?>