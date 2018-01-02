<!DOCTYPE html>

<html>

    <head>
        <title>Podatki o preteklih narocilih</title>
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
                        <div class="col-md-8">
                            <h1>Vsa ustvarjena narocila</h1>
                            <ul class="list-group">
                                <?php foreach ($narocila as $narocilo):?>
                                    <li class="list-group-item">
                                        <p> Narocilo: <?= $narocilo['id'] ?></p>
                                        <p> Stanje narocila: <b> <?= $narocilo['stanje'] ?> </b> </p>
                                        <p> Skupna cena: <?= $narocilo['postavka'] ?> €</p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>