<!DOCTYPE html>

<html>

    <head>
        <title>Prijava</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid p-4 m-auto col-5">
            <div class="mb-3">
                <h2>Prijava</h2>
                <?php echo $form; ?>
            </div>
            <a href="<?= BASE_URL . "x509login" ?>">Prijava za osebje</a>
        </div>
    </body>

</html>