<!DOCTYPE html>
<html>

    <head>
        <title>Nadzorna plosca</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
       <li>
             <a class="nav-link" href="<?= BASE_URL . "prikaz-uporabnikov?vloga=prodajalec" ?>">Vsi prodajalci</a>
        </li>
        <li>
            <a class="nav-link" href="<?= BASE_URL . "registracija-prodajalec" ?>">Registriraj prodajalca</a>
        </li>
    </body>
</html>
