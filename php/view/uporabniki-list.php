<!DOCTYPE html>

<html>

    <head>
        <title>Uporabniki</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="<?= BASE_URL . "izdelki" ?>""> <span class="badge pull-right"></span> 
                                Nazaj
                            </a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-8">
                            <h1>Vsi uporabniki z vlogo: "<?= $_GET['vloga'] ?>"</h1>
                            <?php foreach ($uporabniki as $uporabnik): ?>
                                <p> Ime: <?= $uporabnik['ime'] ?></p>
                                <p> Priimek: <?= $uporabnik['priimek'] ?> </p>
                                <?php if ($uporabnik['aktiven'] == 1): ?>
                                <div class="alert alert-success"> Uporabnik je aktiven </div>
                                <!-- ce je aktiven, ga lahko zbrisemo - deaktiviramo -->
                                <form action="<?= BASE_URL . "deaktiviraj-uporabnika" ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= $uporabnik['id'] ?>" />
                                    <input type="submit" value="Izbrisi uporabnika" />
                                </form>
                                <?php else: ?>
                                <div class="alert alert-danger"> Uporabnik je deaktiviran</div>
                                <?php endif ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>