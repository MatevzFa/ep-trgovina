<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Trgovina</title>

<h1>Vsi izdelki</h1>

<p>[
    <a href="<?= BASE_URL . "izdelki" ?>">Vsi izdelki</a>
    ]</p>

<ul>

    <?php foreach ($izdelki as $izdelek): ?>
        <li>
            <a href="<?= BASE_URL . "izdelki?id=" . $izdelek["id"] ?>">
                <?= $izdelek["ime"] ?> (<?= $izdelek["cena"] ?>)
            </a>
        </li>
    <?php endforeach; ?>

</ul>