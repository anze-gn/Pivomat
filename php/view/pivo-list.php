<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1>Seznam vseh piv</h1>

<p>[ <a href="<?= BASE_URL . "piva/add" ?>">Dodaj novo</a> ]</p>

<h3>Aktivirana:</h3>
<ul>
    <?php foreach ($piva as $pivo): ?>
        <li>
            <a href="<?= BASE_URL . "piva?id=" . $pivo["id"] ?>">
                <?= $pivo["idZnamka"] ?>: <?= $pivo["naziv"] ?> (<?= $pivo["idStil"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<br>
<h3>Deaktivirana:</h3>
<ul>
    <?php foreach ($neaktivnaPiva as $pivo): ?>
        <li>
            <a href="<?= BASE_URL . "piva?id=" . $pivo["id"] ?>">
                <?= $pivo["idZnamka"] ?>: <?= $pivo["naziv"] ?> (<?= $pivo["idStil"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?= HtmlTemplates::foot(); ?>