<!--<!DOCTYPE html>

<html>
    
<head>
    <meta charset="utf-8">
</head>
<body>-->
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
        
        var_dump(UporabnikDB::getAll());
        UporabnikDB::delete(1);
        // fail zaradi FK constrainta
        var_dump(UporabnikDB::getAll());
    ?>
<!--</body>

</html>-->
