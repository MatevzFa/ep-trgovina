<!DOCTYPE html>

<html>
    
<head>
    <meta charset="utf-8">
</head>
<body>
    <?php
    
        require_once '../dbaccess/Uporabnik.php';
        var_dump(Uporabnik::findAll());
        var_dump(Uporabnik::findById(1));
        var_dump(Uporabnik::findById(2));
    ?>
    čćšž
</body>

</html>
