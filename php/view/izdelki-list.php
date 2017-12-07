<!DOCTYPE html>

<head>
    <title>Trgovina</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
    <link rel="stylesheet" href="<?= BOOTSTRAP . "css/bootstrap.min.css" ?>">
    <script src="<?= BOOTSTRAP . "js/bootstrap.min.js" ?>"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="container col-md-9">
                <h1 class="p-md-5">
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
                        <div class="col-md-4 p-md-1">
                            <a href="<?= BASE_URL . "izdelki?id=" . $izdelek["id"] ?>">
                                <div class="izdelek card">
                                    <img class="card-img-top" src="<?= isset($izdelek["slika"]) ? $izdelek['slika'] : IMAGES_URL . 'default.png' ?>">
                                    <div class="card-body">
                                        <h4><?= $izdelek["ime"] ?></h4>
                                        <p>(<?= $izdelek["cena"] ?>)</p>
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
                            <a class="page-link" href="izdelki?offset=<?= $stran * 18 ?>&limit=18"><?= $stran + 1 ?></a>
                        </li>
                    <?php endforeach; ?>
                    <!--<li class="page-item"><a class="page-link" href="#">Naprej</a></li>-->
                </ul>
            </div>
            <div class="col-md-3">
                <h1> Tukaj je lahko cart? </h1>
            </div>
        </div>
    </div>
</body>