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
                            <td>
                                <form action="<?= BASE_URL . "kosarica" ?>" method="post">
                                    <input type="hidden" name="do" value="posodobi" />
                                    <input type="hidden" name="id" value="<?= $izdelek['id'] ?>" />
                                    <input type="number" name="kolicina" value="<?= $izdelek['kolicina'] ?>" class="short_input" />
                                    <button class="btn btn-primary" type="submit">V</button>
                                </form></td>
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
            <form action="<?= BASE_URL . "kosarica" ?>" method="post" id="form_izprazni">
                <input type="hidden" name="do" value="izprazni" />
            </form>
            <button class="btn btn-danger" type="submit" form="form_izprazni">Izprazni košarico</button>


        </div>        
    </body>

</html>