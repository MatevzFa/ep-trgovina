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
             <a class="nav-link" href="<?= BASE_URL . "narocila-list?stanje=oddano"?>">Pregled oddanih narocil</a>
        </li>
        <li>
             <a class="nav-link" href="<?= BASE_URL . "narocila-list?stanje=potrjeno"?>">Pregled potrjenih narocil</a>
        </li>
        <li>
             <a class="nav-link" href="<?= BASE_URL . "narocila-list?stanje=stornirano"?>">Pregled storniranih narocil</a>
        </li>
    </body>
</html>