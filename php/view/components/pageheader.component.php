<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= BASE_URL ?>">EP trgovina</a>
    
    <div class="navbar-expand" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "prijava" ?>">Prijava</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "registracija-nov-uporabnik" ?>">Registracija</a>
                </li>
            <?php endif; ?>

            <!-- Gumbi za administracijo -->
            <?php if (isset($_SESSION['user_vloga'])): ?>
                <?php if ($_SESSION['user_vloga'] == 'administrator'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "administrator-nadzorna-plosca" ?>">Nadzorna plosca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                    </li>
                    <li class="nav-item"> 
                        <div class="alert alert-warning">Vloga: <?= $_SESSION['user_vloga'] ?></div>
                    </li>
                <?php elseif ($_SESSION['user_vloga'] == 'prodajalec'): ?>
                     <!-- samo za debugganje, drugace bo samo pri adminu -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "administrator-nadzorna-plosca" ?>">Nadzorna plosca(admin)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "prodajalec-nadzorna-plosca" ?>">Nadzorna plosca</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL . "profil" ?>"><?= UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']))['ime'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . 'kosarica' ?>">Moja košarica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "narocila" ?>">Pregled narocil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                    </li>
                    <li class="nav-item">
                        <div class="alert alert-warning">Vloga: <?= $_SESSION['user_vloga'] ?></div>
                    </li>
                <?php else: ?>    
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL . "profil" ?>"><?= UporabnikDB::podatkiOUporabniku(array('id' => $_SESSION['user_id']))['ime'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . 'kosarica' ?>">Moja košarica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "narocila" ?>">Pregled narocil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                    </li>
                    <li class="nav-item"> 
                        <div class="alert alert-warning">Vloga: <?= $_SESSION['user_vloga'] ?></div>
                    </li>
                <?php endif; ?>
            <?php endif ?>
        </ul>
    </div>
</nav>