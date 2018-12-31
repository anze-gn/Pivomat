<?php require_once("HtmlTemplates.php"); ?>

<?= HtmlTemplates::head($title); ?>

<h1>Seznam vseh prodajalcev</h1>

<p>[ <a href="<?= BASE_URL . "prodajalci/add" ?>">Dodaj novega prodajalca</a> ]</p>

<h3>Aktivirani:</h3>
<ul>
    <?php foreach ($prodajalci as $prodajalec): ?>
        <li>
            <a href="<?= BASE_URL . "prodajalci?id=" . $prodajalec["id"] ?>">
                <?= $prodajalec["ime"] ?> <?= $prodajalec["priimek"] ?> (<?= $prodajalec["email"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<br>
<h3>Deaktivirani:</h3>
<ul>
    <?php foreach ($neaktivniProdajalci as $prodajalec): ?>
        <li>
            <a href="<?= BASE_URL . "prodajalci?id=" . $prodajalec["id"] ?>">
                <?= $prodajalec["ime"] ?> <?= $prodajalec["priimek"] ?> (<?= $prodajalec["email"] ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?= HtmlTemplates::foot(); ?>