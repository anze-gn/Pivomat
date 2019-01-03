<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1>Seznam vseh strank</h1>

<p>[ <a href="<?= BASE_URL . "stranke/add" ?>">Dodaj novo stranko</a> ]</p>

<h3>Aktivirane:</h3>
<ul>
    <?php foreach ($stranke as $stranka): ?>
        <li>
            <a href="<?= BASE_URL . "stranke/" . $stranka["id"] ?>">
                <?= $stranka["ime"] ?> <?= $stranka["priimek"] ?> (<?= $stranka["email"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<br>
<h3>Deaktivirane:</h3>
<ul>
    <?php foreach ($neaktivneStranke as $stranka): ?>
        <li>
            <a href="<?= BASE_URL . "stranke/" . $stranka["id"] ?>">
                <?= $stranka["ime"] ?> <?= $stranka["priimek"] ?> (<?= $stranka["email"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?= HtmlTemplates::foot(); ?>