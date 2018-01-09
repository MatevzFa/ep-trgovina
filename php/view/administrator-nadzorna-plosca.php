<!DOCTYPE html>
<html>

    <head>
        <title>Nadzorna plosca</title>
        <?php include(HEAD); ?>
    </head>
    <body>
        <?php include(NAVBAR); ?>
        <?php if ($_SESSION['user_vloga'] == 'administrator'):?>
            <div class="list-group">
                 <a class="list-group-item" href="<?= BASE_URL . "prikaz-uporabnikov?vloga=prodajalec" ?>">Vsi prodajalci</a>
                 <a class="list-group-item" href="<?= BASE_URL . "registracija-prodajalec" ?>">Registriraj prodajalca</a>
            </div>
        <?php else: ?>
            <a href="<?= BASE_URL . "x509login" ?>">Prijavite se kot administrator</a>
        <?php endif; ?>
    </body>
</html>
