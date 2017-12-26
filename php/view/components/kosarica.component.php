<?php foreach ($kosarica as $izdelek): ?>
    <div class="my-1 border rounded row">
        <div class="col-6"><a href="<?= BASE_URL . 'izdelki?id=' . $izdelek['id'] ?>"><?= $izdelek['ime'] ?></a></div>
        <div class="col-2"><?= $izdelek['kolicina']?></div>
        <div class="col-4"><?= $izdelek['kolicina'] * $izdelek['cena']?></div>
    </div>
<?php endforeach; ?>