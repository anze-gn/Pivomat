<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head("stranka"); ?>

<h1><?= $stranka["ime"] . " " . $stranka["priimek"] ?></h1>

<p>[ <a href="<?= BASE_URL . "stranke" ?>">Seznam vseh strank</a> ]</p>

<ul>
    <li>E-mail:             <b><?= $stranka["email"] ?>   </b></li>
    <li>Ulica:              <b><?= $stranka["ulica"] ?>   </b></li>
    <li>Hišna številka:     <b><?= $stranka["hisnaSt"] ?>   </b></li>
    <li>Poštna številka:    <b><?= $stranka["postnaSt"] ?>  </b></li>
    <li>Kraj:               <b><?= $stranka["imeKraja"] ?>   </b></li>
    <li>Telefon:            <b><?= $stranka["telefon"] ?>   </b></li>
    <li>Geslo:              <b><?= $stranka["geslo"] ?>   </b></li>
</ul>

<p>[ <a href="<?= BASE_URL . "stranke/edit?id=" . $stranka["id"] ?>">Uredi</a> ]</p>

<?= HtmlTemplates::foot(); ?>