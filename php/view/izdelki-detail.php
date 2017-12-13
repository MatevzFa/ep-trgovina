<!DOCTYPE html>

<html>

    <head>
        <title>Trgovina</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="<?= BASE_URL . "izdelki" ?>""> <span class="badge pull-right"></span> Nazaj</a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-6">
                            <h1><?= $izdelek['ime'] ?></h1>
                            <?php foreach ($slike as $slika): ?>
                                <img class="card-img-top" 
                                             src="<?= IMAGES_URL . $slika ?>">
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <p><?= $izdelek['cena'] ?> €</p>
                            <p><?= $izdelek['opis'] ?></p>
                            <p>Povprecna ocena: <?= isset($izdelek['povprecnaOcena']) ? $izdelek['povprecnaOcena'] : "Ni ocen" ?></p>
                            <div class="container">
                                oceni izdelek
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </body>

</html>