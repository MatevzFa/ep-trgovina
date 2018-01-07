<!DOCTYPE html>

<html>

    <head>
        <title>Urejanje izdelka</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container">
            <a href="<?= BASE_URL . "prikaz-izdelkov-cmp" ?>""> 
                Nazaj na vse izdelke
            </a>
            <h1> Urejanje izdelka </h1>
            <?php if (isset($_SESSION['user_id'])):?>
                <form action="<?= BASE_URL . "urejanje-izdelka" ?>" method="POST">
                    Ime izdelka: <input type="text" name="ime" value="<?= $izdelek['ime'] ?>"><br>
                    Opis izdelka: <textarea type="text" rows="4" cols="50" name="opis"><?= $izdelek['opis']?></textarea><br>
                    Cena izdelka: <input type="number" step="0.01" min="0" name="cena" value="<?= $izdelek['cena']?>"><br>
                    <input type="hidden" name="id" value="<?= $izdelek['id'] ?>" />
                    <input type="submit" value="Uredi izdelek">
                </form>
            <?php else: ?>
                <a href="<?= BASE_URL . "prijava" ?>">Prijavite se</a>
            <?php endif; ?>
        </div>
    </body>

</html>