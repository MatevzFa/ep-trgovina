<!DOCTYPE html>

<html>

    <head>
        <title>Košarica</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Izdelek</th>
                        <th scope="col">Količina</th>
                        <th scope="col">Cena</th>
                        <th scope="col">Cena skupaj</th>
                    </tr>
                </thead>

                <input type="hidden" name="do" value="posodobi" />
                <tbody>

                    <?php
                    $cena_skupaj = 0;

                    foreach ($kosarica as $izdelek):
                        $cena_skupaj += $izdelek['cena'] * $izdelek['kolicina'];
                        ?>
                        <tr>
                            <td><a href="<?= BASE_URL . 'izdelki?id=' . $izdelek['id'] ?>"><?= $izdelek['ime'] ?></a></td>
                            <td><?= $izdelek['kolicina'] ?></td>
                            <td><?= $izdelek['cena'] ?>€</td>
                            <td><?= $izdelek['kolicina'] * $izdelek['cena'] ?>€</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"><?= $cena_skupaj ?>€</th>
                    </tr>
                </tfoot>
            </table>            
            
            <form action="<?= BASE_URL . "kosarica/zakljuci" ?>" method="post" id="form_oddaj">
                <input type="hidden" name="do" value="izprazni" />
            </form>
            <a class="btn btn-outline-primary" href="<?= BASE_URL . "kosarica" ?>">Nazaj na urejanje</a>
            <button class="btn btn-primary" type="submit" form="form_oddaj">Oddaj naročilo</button>

        </div>        
    </body>

</html>