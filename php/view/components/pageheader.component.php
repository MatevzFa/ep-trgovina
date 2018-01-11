<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= BASE_URL ?>">EP trgovina</a>

    <div class="navbar-expand" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">


            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_vloga'])): ?>

                <?php
                $pagehdrUser = UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']));
                ?>

                <?php if ($_SESSION['user_vloga'] == 'administrator'): ?>,

                    <!-- Gumbi za administratorja -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "administrator-nadzorna-plosca" ?>">Nadzorna plosca</a>
                    </li>

                <?php elseif ($_SESSION['user_vloga'] == 'prodajalec'): ?>

                    <!--Gumbi za prodajalca-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "prodajalec-nadzorna-plosca" ?>">Nadzorna plosca</a>
                    </li>

                <?php else: ?>    

                    <!--Gumbi za stranke -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . 'kosarica' ?>">Moja košarica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "narocila" ?>">Pregled narocil</a>
                    </li>

                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL . "profil" ?>"><?= $pagehdrUser['ime'] . " " . $pagehdrUser['priimek'] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                </li>
                <li class="nav-item"> 
                    <span class="nav-link text-warning">Vloga: <?= $_SESSION['user_vloga'] ?></span>
                </li>

            <?php else: ?>

                <!--Ni prijavljen-->
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "prijava" ?>">Prijava</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "registracija-captcha" ?>">Registracija</a>
                </li>

            <?php endif ?>

        </ul>
    </div>
</nav>