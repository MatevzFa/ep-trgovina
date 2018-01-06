<!DOCTYPE html>

<html>

    <head>
        <title>Prikaz in urejanje izdelkov</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="container col-md-12 border border-top-0 border-bottom-0 border-left-0">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_vloga'] == 'prodajalec'):?>
                        <!-- ne vem ce je pametno tukaj preveriti ali je prodajalec...rajsi na serverju? -->
                        <a href="<?= BASE_URL . "prodajalec-nadzorna-plosca" ?>">
                            Nazaj na nadzorno plosco
                        </a>
                        <h1 class="p-md-5">
                            Urejanje in prikaz vseh izdelkov
                        </h1>
                        <p> Za urejanje izdelka pritisnite na njegovo ikono </p>

                        <ul class="pagination">
                            <!--<li class="page-item"><a class="page-link" href="#">Nazaj</a></li>-->
                            <?php foreach (range(0, floor($stIzdelkov / 18)) as $stran): ?>
                                <li class="page-item">
                                    <a class="page-link" href="prikaz-izdelkov-cmp?offset=<?= $stran * 18 ?>&limit=18"><?= $stran + 1 ?></a>
                                </li>
                            <?php endforeach; ?>
                            <!--<li class="page-item"><a class="page-link" href="#">Naprej</a></li>-->
                        </ul>

                        <div class="row">
                            <?php foreach ($izdelki as $izdelek): ?>
                                <div class="col-md-2 p-md-1">
                                    <a href="<?= BASE_URL . "prikaz-izdelkov-cmp?id=" . $izdelek["id"] ?>">
                                        <div class="izdelek card">
                                            <img class="card-img-top" 
                                                 src="<?= isset($izdelek["slika"]) ? IMAGES_URL . $izdelek['slika'] : IMAGES_URL . 'default.png' ?>">
                                            <div class="card-body">
                                                <h4><?= $izdelek["ime"] ?></h4>
                                                <p>(<?= $izdelek["cena"] ?> €)</p>
                                            </div>                                
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <ul class="pagination">
                            <!--<li class="page-item"><a class="page-link" href="#">Nazaj</a></li>-->
                            <?php foreach (range(0, floor($stIzdelkov / 18)) as $stran): ?>
                                <li class="page-item">
                                    <a class="page-link" href="prikaz-izdelkov-cmp?offset=<?= $stran * 18 ?>&limit=18"><?= $stran + 1 ?></a>
                                </li>
                            <?php endforeach; ?>
                            <!--<li class="page-item"><a class="page-link" href="#">Naprej</a></li>-->
                        </ul>
                    <?php else: ?>
                        <a href="<?= BASE_URL . "prijava" ?>">Prijavite se</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>

</html>