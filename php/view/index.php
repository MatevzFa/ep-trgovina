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
        
        var_dump(UporabnikDB::get(1));
        UporabnikDB::posodobiGeslo(1, "7ffedf68");
        var_dump(UporabnikDB::get(1));
        var_dump(UporabnikDB::preveriGeslo(1, "7ffedf68"));
    ?>
<!--</body>

</html>-->
