<?php
require_once 'forms/RegistracijaForm.php';

$url = basename(__FILE__);

?>

<!doctype html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Registracija novega uporabnika</title>
</head>
<body>
    <?php
    echo $form;
    ?>
</body>
