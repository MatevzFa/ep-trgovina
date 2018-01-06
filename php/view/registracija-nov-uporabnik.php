<!DOCTYPE html>

<html>

    <head>
        <title>Registracija</title>
        <?php include(HEAD); ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container">
            <h1> Registracija novega uporabnika </h1>
                <form action="<?= BASE_URL . "registracija-captcha" ?>" method="POST">
                    Ime: <input type="text" name="ime"><br>
                    Priimek: <input type="text" name="priimek"><br>
                    Email: <input type="text" name="email"><br>
                    Naslov: <input type="text" name="naslov"><br>
                    Telefon: <input type="text" name="telefon"><br>
                    Geslo (vsaj 6 znakov): <input pattern=".{6,}" oninvalid="this.setCustomValidity('Zahtevana dolzina vsaj 6 znakov')" type="password" name="geslo"><br>
                    <div class="g-recaptcha" data-sitekey="6LeIVT8UAAAAAKp9SetpBLSOv2HNx2DyUZ5Nx0bo"></div>
                    <input type="submit" value="Registriraj se">
                </form>
        </div>
    </body>

</html>