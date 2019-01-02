<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head("admin"); ?>

<h1><?= $admin["ime"] . " " . $admin["priimek"] ?></h1>

<p>[ <a href="<?= BASE_URL . "prodajalci" ?>">Seznam vseh prodajalcev</a> ]</p>

<ul>
    <li>Ime:                <b><?= $admin["ime"] ?>     </b></li>
    <li>Priimek:            <b><?= $admin["priimek"] ?> </b></li>
    <li>E-mail:             <b><?= $admin["email"] ?>   </b></li>
</ul>

<p>[ <a href="<?= BASE_URL . "admin/edit" ?>">Uredi</a> ]</p>

<?= HtmlTemplates::foot(); ?>