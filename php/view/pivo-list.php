<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1>Seznam vseh piv</h1>

<p>[ <a href="<?= BASE_URL . "piva/add" ?>">Dodaj novo</a> ]</p>

<ul>

    <?php foreach ($piva as $pivo): ?>
        <li>
            <a href="<?= BASE_URL . "piva?id=" . $pivo["id"] ?>">
                <?= $pivo["znamka"] ?>: <?= $pivo["naziv"] ?> (<?= $pivo["stil"] ?>)
            </a>
        </li>
    <?php endforeach; ?>

</ul>

<?= HtmlTemplates::foot(); ?>