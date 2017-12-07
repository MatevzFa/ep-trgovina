<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
    </head>

    <body>


        <h2> Testiranje gesel </h2>

        <?php
//        require_once '../model/Uporabnik.php';
//        var_dump(Uporabnik::findAll());
//        var_dump(Uporabnik::findById(1));
//        var_dump(Uporabnik::findById(2));
//        
//        require_once '../model/Izdelek.php';
//        var_dump(Izdelek::getAllItems());
//        var_dump(Izdelek::getItemInformation(2));

        
        require_once '../model/db/UporabnikDB.php';

        var_dump(UporabnikDB::get(array('id' => 1)));
        UporabnikDB::posodobiGeslo(1, "testnogeslo");
        var_dump(UporabnikDB::get(array('id' => 1)));
        var_dump(UporabnikDB::preveriGeslo(1, "testnogeslo"));
        var_dump(UporabnikDB::preveriGeslo(1, "testnogesloooo"));
        ?>

        <h2> Testiranje getAll, getAllFromCustomer in getOrderDetails narocil </h2>
        <?php
        require_once '../model/db/NarociloDB.php';
        var_dump(NarociloDB::getAll());
        var_dump(NarociloDB::pridobiVsaNarocilaStranke(array('uporabnik_id' => 4)));
        var_dump(NarociloDB::pridobiPodrobnostiONarocilu(array('id' => 1)));
        ?>

        <h2> Testiranje get za izdelek z ID 2 in getAll </h2>

        <?php
        require_once '../model/db/IzdelekDB.php';
        var_dump(IzdelekDB::pridobiSlike(array('id' => 2)));
        var_dump(IzdelekDB::getAll());
        ?>


        <h2> Testiranje inserta Izdelka. ID je auto increment. </h2>

        <?php
        $izdelekZaDodat = [ 'ime' => 'Adobe Photoshop', 'cena' => '13.37', 'opis' => 'Edit pictures and stuff.'];
        IzdelekDB::insert($izdelekZaDodat);
        var_dump(IzdelekDB::getAll());
        ?>

    </body>

</html