<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($prodajalec["ime"] . " " . $prodajalec["priimek"]); ?>

<h1><?= $prodajalec["ime"] . " " . $prodajalec["priimek"] ?></h1>

<p>[ <a href="<?= BASE_URL . "prodajalci" ?>">Seznam vseh prodajalcev</a> ]</p>

<ul>
    <li>E-mail:             <b><?= $prodajalec["email"] ?>   </b></li>
    <li>Geslo:               <b><?= $prodajalec["geslo"] ?>     </b></li>
</ul>

<p>[ <a href="<?= BASE_URL . "prodajalci/edit?id=" . $prodajalec["id"] ?>">Uredi</a> ]</p>

<?= HtmlTemplates::foot(); ?>