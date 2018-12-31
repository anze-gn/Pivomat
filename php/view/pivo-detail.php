<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($pivo["naziv"]); ?>

<h1><?= $pivo["naziv"] ?></h1>

<p>[ <a href="<?= BASE_URL . "piva" ?>">Seznam vseh piv</a> ]</p>

<ul>
    <li>Znamka:             <b><?= $pivo["idZnamka"] ?>   </b></li>
    <li>Stil:               <b><?= $pivo["idStil"] ?>     </b></li>
    <li>Količina:           <b><?= $pivo["kolicina"] ?>l </b></li>
    <li>Vsebnost alkohola:  <b><?= number_format($pivo["alkohol"], 1, '.', ''); ?>% </b></li>
    <li>Cena:               <b><?= number_format($pivo["cena"], 2, '.', ''); ?>€    </b></li>
    <li>Opis:               <i><?= $pivo["opis"] ?>     </i></li>
</ul>

<p>[ <a href="<?= BASE_URL . "piva/edit?id=" . $pivo["id"] ?>">Uredi</a> ]</p>

<?= HtmlTemplates::foot(); ?>
