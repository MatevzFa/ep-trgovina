<!DOCTYPE html>

<html>

    <head>
        <title>Nadzorna plosca</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
        <div class="list-group" style="width: 40%;">
            <a class="list-group-item" href="<?= BASE_URL . "izdelki-add" ?>">Dodaj nov izdelek</a>
            <a class="list-group-item" href="<?= BASE_URL . "registracija" ?>">Dodaj novo stranko</a>
            <a class="list-group-item" href="<?= BASE_URL . "prikaz-uporabnikov?vloga=stranka" ?>">Vse stranke</a>
            <a class="list-group-item" href="<?= BASE_URL . "prikaz-izdelkov-cmp" ?>">Vsi izdelki</a>
            <a class="list-group-item" href="<?= BASE_URL . "narocila-list?stanje=oddano"?>">Pregled oddanih narocil</a>
            <a class="list-group-item list-group-item-success" href="<?= BASE_URL . "narocila-list?stanje=potrjeno"?>">Pregled potrjenih narocil</a>
            <a class="list-group-item list-group-item-danger" href="<?= BASE_URL . "narocila-list?stanje=preklicano"?>">Pregled preklicanih narocil</a>
            <a class="list-group-item list-group-item-warning" href="<?= BASE_URL . "narocila-list?stanje=stornirano"?>">Pregled storniranih narocil</a>
        </div>
    </body>
</html>