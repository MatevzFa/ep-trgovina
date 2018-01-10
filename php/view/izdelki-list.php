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
                <div class="container col-md-9 border border-top-0 border-bottom-0 border-left-0 p-4">
                    <h1>
                        Vsi izdelki
                    </h1>

                    <ul class="pagination">
                        <!--<li class="page-item"><a class="page-link" href="#">Nazaj</a></li>-->
                        <?php foreach (range(0, floor($stIzdelkov / 18)) as $stran): ?>
                            <li class="page-item">
                                <a class="page-link" href="izdelki?offset=<?= $stran * 18 ?>&limit=18"><?= $stran + 1 ?></a>
                            </li>
                        <?php endforeach; ?>
                        <!--<li class="page-item"><a class="page-link" href="#">Naprej</a></li>-->
                    </ul>

                    <div class="row">
                        <?php foreach ($izdelki as $izdelek): ?>
                            <?php if ($izdelek['aktiven'] == 1): ?>
                                <div class="col-md-4 p-md-1">
                                    <a href="<?= BASE_URL . "izdelki?id=" . $izdelek["id"] ?>">
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
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <ul class="pagination">
                        <!--<li class="page-item"><a class="page-link" href="#">Nazaj</a></li>-->
                        <?php foreach (range(0, floor($stIzdelkov / 18)) as $stran): ?>
                            <li class="page-item">
                                <a class="page-link" href="izdelki?offset=<?= $stran * 18 ?>&limit=18"><?= $stran + 1 ?></a>
                            </li>
                        <?php endforeach; ?>
                        <!--<li class="page-item"><a class="page-link" href="#">Naprej</a></li>-->
                    </ul>
                </div>
                <div class="col-md-3 p-0">
                    <h2 class="px-3 pt-3"><a href="<?= BASE_URL . 'kosarica' ?>">Košarica</a></h2>
                    <div class="table-responsive">
                        <?php include('components/kosarica.component.php'); ?>
                    </div>

                </div>
            </div>
        </div>
    </body>

</html>