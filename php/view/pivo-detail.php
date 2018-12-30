<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($pivo["naziv"]); ?>

<h1><?= $pivo["naziv"] ?></h1>

<p>[ <a href="<?= BASE_URL . "piva" ?>">Seznam vseh piv</a> ]</p>

<ul>
    (aktiviran, posodobljen, naziv, znamka, opis, kolicina, alkohol, cena, stil)
    <li>Znamka:             <b><?= $pivo["znamka"] ?>   </b></li>
    <li>Opis:               <b><?= $pivo["stil"] ?>     </b></li>
    <li>Količina:           <b><?= $pivo["kolicina"] ?> </b></li>
    <li>Vsebnost alkohola:  <b><?= $pivo["alkohol"] ?>% </b></li>
    <li>cena:               <b><?= $pivo["cena"] ?>€    </b></li>
    <li>opis:               <i><?= $pivo["opis"] ?>     </i></li>
</ul>

<p>[ <a href="<?= BASE_URL . "piva/edit?id=" . $pivo["id"] ?>">Uredi</a> ]</p>

<?= HtmlTemplates::foot(); ?>
