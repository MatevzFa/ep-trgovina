<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Trgovina</title>

<?php
    var_dump($izdelek);
?>

<h1><?= $izdelek['ime'] ?></h1>

<p><?= $izdelek['cena'] ?></p>
<p><?= $izdelek['opis'] ?></p>
