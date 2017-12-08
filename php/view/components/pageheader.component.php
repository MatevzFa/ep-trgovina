<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= BASE_URL ?>">EP trgovina</a>

    <div class="navbar-expand" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "prijava" ?>">Prijava</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "registracija" ?>">Registracija</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= BASE_URL . "profil" ?>"><?= UporabnikDB::get(array('id' => $_SESSION['user_id']))['ime'] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>