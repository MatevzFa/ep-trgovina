<!DOCTYPE html>
<html>

    <head>
        <title>Nadzorna plosca</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
       <div class="list-group">
            <a class="list-group-item" href="<?= BASE_URL . "prikaz-uporabnikov?vloga=prodajalec" ?>">Vsi prodajalci</a>
            <a class="list-group-item" href="<?= BASE_URL . "registracija-prodajalec" ?>">Registriraj prodajalca</a>
       </div>
    </body>
</html>
