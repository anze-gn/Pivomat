<?php
    require_once("HtmlTemplates.php");
?>

<?= HtmlTemplates::head($title); ?>

<h1><?= $title ?></h1>

<h3>Potrditveno elektronsko sporočilo je na poti. Pred prijavo kliknite na povezavo v sporočilu.</h3>

<p>Če v nekaj minutah ne prejmete sporočila, kontaktirajte administratorja ali poskusite s ponovno registracijo.</p>

<p>[
    <a href="<?= BASE_URL . "prijava" ?>">Prijavi se</a>
]</p>

<?= isset($error) ? $error : ""?>

<?= HtmlTemplates::foot(); ?>