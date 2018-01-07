<!DOCTYPE html>

<html>

    <head>
        <title>Urejanje in pregled profila</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container">
            <h1> Urejanje in pregled profila </h1>
            <?php if (isset($_SESSION['user_id'])):?>
                <form action="<?= BASE_URL . "urejanje-zaposleni" ?>" method="post">
                    Ime: <input type="text" name="ime" value="<?= $podatki['ime'] ?>"><br>
                    Priimek: <input type="text" name="priimek" value="<?= $podatki['priimek'] ?>"><br>
                    Email: <input type="text" name="email" value="<?= $podatki['email'] ?>"><br>
                     <input type="hidden" name="id" value="<?= $podatki['id'] ?>" />
                     <input type="submit" value="Spremeni osebne podatke">
                </form>
            <br>
                <form action="<?= BASE_URL . "spremeni-geslo" ?>" method="post">
                    Novo geslo (vsaj 6 znakov): <input pattern=".{6,}" oninvalid="this.setCustomValidity('Zahtevana dolzina vsaj 6 znakov')" type="password" name="geslo"><br>
                     <input type="hidden" name="id" value="<?= $podatki['id'] ?>" />
                     <input type="submit" value="Spremeni geslo">
                </form>
            <?php else: ?>
                <a href="<?= BASE_URL . "prijava" ?>">Prijavite se</a>
            <?php endif; ?>
        </div>
    </body>

</html>