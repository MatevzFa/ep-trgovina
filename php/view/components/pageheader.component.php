<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= BASE_URL ?>">EP trgovina</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                    <a class="nav-link disabled text-white">Pozdravljen <?= $_SESSION['user_id'] ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL . "odjava" ?>">Odjava</a>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>