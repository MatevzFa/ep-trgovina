<!DOCTYPE html>

<html>

    <head>
        <title>Dodajanje novega izdelka</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_vloga'] == 'prodajalec'):?>
            <div class="container">
            <a href="<?= BASE_URL . "prodajalec-nadzorna-plosca" ?>""> <span class="badge pull-right"></span> 
                Nazaj na nadzorno plosco
            </a>
               <h1> Dodajanje novega izdelka </h1>
                    <form action="<?= BASE_URL . "izdelki-add" ?>" method="POST" enctype="multipart/form-data">
                        Ime izdelka: <input type="text" name="ime"><br>
                        Opis izdelka: <textarea type="text" rows="4" cols="50" name="opis" placeholder="Opis novega izdelka..."></textarea><br>

                        Cena izdelka: <input type="number" step="0.01" min="0" name="cena"><br>
                        Slika izdelka: <input accept="image/*" type="file" name="slika"> <br><br>
                        <input type="submit" value="Dodaj izdelek">
                    </form>
            </div>
        <?php else: ?>
            <a href="<?= BASE_URL . "x509login" ?>">Prijavite se kot prodajalec</a>
        <?php endif; ?>
    </body>

</html>