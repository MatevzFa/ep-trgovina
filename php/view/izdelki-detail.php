<!DOCTYPE html>

<head>
	<title>Trgovina</title>
	<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills">
					<li class="active">
						 <a href="<?= BASE_URL . "izdelki" ?>""> <span class="badge pull-right"></span> Nazaj</a>
					</li>
				</ul>
				<div class="row">
					<div class="col-md-8">
						<h1><?= $izdelek['ime'] ?></h1>
						<?php foreach ($slike as $slika): ?>
					        <p> Slika: <?= $slika['path'] ?></p>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<p><?= $izdelek['cena'] ?> £</p>
						<p><?= $izdelek['opis'] ?></p>
						<p>Povprecna ocena: <?= $izdelek['povprecnaOcena'] ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
