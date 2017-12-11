<!DOCTYPE html>

<html>

    <head>
        <title>Nadzorna plosca</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
        <li>
             <a class="nav-link" href="<?= BASE_URL . "izdelki-add" ?>">Dodaj nov izdelek</a>
        </li>
        <li>
             <a class="nav-link" href="<?= BASE_URL . "registracija" ?>">Dodaj novo stranko</a>
        </li>
        <li>
             Pregled oddanih narocil
        </li>
        <li>
             Pregled potrjenih narocil
        </li>
        <li>
             Pregled vseh narocil
        </li>
    </body>
</html>