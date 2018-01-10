<table class="table table-hover">
    <?php foreach ($kosarica as $izdelek): ?>
        <tr>
            <td class=""><a href="<?= BASE_URL . 'izdelki?id=' . $izdelek['id'] ?>"><?= $izdelek['ime'] ?></a></td>
            <td class=""><?= $izdelek['kolicina'] ?></td>
            <td class=""><?= $izdelek['kolicina'] * $izdelek['cena'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>