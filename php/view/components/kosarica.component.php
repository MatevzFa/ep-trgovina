<table class="table table-hover">
    <?php foreach ($kosarica as $izdelek): ?>
        <!-- <div class="border rounded row">
            <div class="col-6"><a href="<?= BASE_URL . 'izdelki?id=' . $izdelek['id'] ?>"><?= $izdelek['ime'] ?></a></div>
            <div class="col-2"><?= $izdelek['kolicina'] ?></div>
            <div class="col-4"><?= $izdelek['kolicina'] * $izdelek['cena'] ?></div>
        </div> -->

        <tr>
            <td class=""><a href="<?= BASE_URL . 'izdelki?id=' . $izdelek['id'] ?>"><?= $izdelek['ime'] ?></a></td>
            <td class=""><?= $izdelek['kolicina'] ?></td>
            <td class=""><?= $izdelek['kolicina'] * $izdelek['cena'] ?></td>
        </tr>


    <?php endforeach; ?>
</table>