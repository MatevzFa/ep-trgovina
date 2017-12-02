<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Trgovina</title>

<?php
    //var_dump($izdelek);
    //var_dump($slike);
    if ($izdelek['povprecnaOcena'] == null) {
    	$izdelek['povprecnaOcena'] = 'Ta izdelek se nima ocene.';
    }
?>

<h1><?= $izdelek['ime'] ?></h1>

<?php foreach ($slike as $slika): ?>
        <p> Slika: <?= $slika['path'] ?></p>
<?php endforeach; ?>

<p><?= $izdelek['cena'] ?></p>
<p><?= $izdelek['opis'] ?></p>
<p>Povprecna ocena: <?= $izdelek['povprecnaOcena'] ?></p>
