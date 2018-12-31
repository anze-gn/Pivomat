<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1>Seznam vseh piv</h1>

<p>[ <a href="<?= BASE_URL . "piva/add" ?>">Dodaj novo</a> ]</p>

<ul>
    <h3>Aktivirana:</h3>
    <?php foreach ($piva as $pivo): ?>
        <li>
            <a href="<?= BASE_URL . "piva?id=" . $pivo["id"] ?>">
                <?= $pivo["idZnamka"] ?>: <?= $pivo["naziv"] ?> (<?= $pivo["idStil"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
    <br>
    <h3>Deaktivirana:</h3>
    <?php foreach ($neaktivnaPiva as $pivo): ?>
        <li>
            <a href="<?= BASE_URL . "piva?id=" . $pivo["id"] ?>">
                <?= $pivo["idZnamka"] ?>: <?= $pivo["naziv"] ?> (<?= $pivo["idStil"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?= HtmlTemplates::foot(); ?>