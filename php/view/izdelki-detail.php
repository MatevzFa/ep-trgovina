<!DOCTYPE html>

<html>

    <head>
        <title>Trgovina</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container">
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
                                <img class="card-img-top" src="<?= IMAGES_URL . $slika['path'] ?>">
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <p><?= $izdelek['cena'] ?> €</p>
                            <p><?= $izdelek['opis'] ?></p>
                            <div class="p-3 mb-2 bg-info text-white">
                                <?php $percentOcena = isset($izdelek['povprecnaOcena']) ? $izdelek['povprecnaOcena'] : 0 ?>
                                Povprecna ocena: <?= isset($izdelek['povprecnaOcena']) ? $izdelek['povprecnaOcena'] : "Ni ocen" ?></p>
                            <br>    
                            <div class="ocenaDIV">
                                    <div>
                                        <img style="max-width: 300px;" src="https://image.ibb.co/jpMUXa/stars_blank.png" alt="img">
                                    </div>
                                    <div class="ocenaSlika" style="width:<?=($percentOcena/5)*100 ?>%;">
                                        <img style="max-width: 300px;" src="https://image.ibb.co/caxgdF/stars_full.png" alt="">
                                    </div>
                             </div>
                            <br><br><br>
                            <div class="container">
                                <?php if ($jeOcenil == False): ?>
                                    Oceni izdelek
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form action="<?= BASE_URL . "oceni-izdelek" ?>" method="post">                                             
                                            <label class="radio-inline"><input type="radio" name="ocena" value="1">1</label>
                                            <label class="radio-inline"><input type="radio" name="ocena" value="2">2</label>
                                            <label class="radio-inline"><input type="radio" name="ocena" value="3">3</label>
                                            <label class="radio-inline"><input type="radio" name="ocena" value="4">4</label>
                                            <label class="radio-inline"><input type="radio" name="ocena" value="5">5</label>
                                            <input type="hidden" name="izdelek_id" value="<?= $izdelek['id'] ?>" />
                                            <input type="submit" value="Oddaj oceno" />
                                        </form>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL . "prijava" ?>">Prijavite se</a>
                                    <?php endif; ?>
                                <?php endif; ?> 
                            </div>
                            <div class="container">
                                <form action="<?= BASE_URL . "kosarica" ?>" method="post">
                                    <input type="hidden" name="do" value="dodaj" />
                                    <input type="hidden" name="id" value="<?= $izdelek['id'] ?>" />
                                    <button type="submit">Dodaj v košarico</button>
                                </form>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </body>

</html>
