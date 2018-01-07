<!DOCTYPE html>

<html>
        <head>
            <title>Registracija novega prodajalca</title>
            <?php include(HEAD); ?>
        </head>

        <body>
            <?php include(NAVBAR); ?>
            <div class="container">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_vloga'] == 'administrator'):?>
                    <?php echo $form; ?>
                <?php else: ?>
                    <a href="<?= BASE_URL . "x509login" ?>">Prijavite se kot administrator</a>
                <?php endif; ?>
            </div>
        </body>
    

</html>