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
                            <?php foreach ($narocila as $narocilo): ?>
                                <p> Narocilo: <?= $narocilo['id'] ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>