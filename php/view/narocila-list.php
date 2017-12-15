<!DOCTYPE html>

<html>

    <head>
        <title>Podatki o preteklih narocilih</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="<?= BASE_URL . "prodajalec-nadzorna-plosca" ?>""> <span class="badge pull-right"></span> 
                                Nazaj na nadzorno plosco
                            </a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-8">
                            <h1>Vsa narocila s stanjem "<?= $_GET['stanje'] ?>"</h1>
                            <?php foreach ($narocila as $narocilo): ?>
                                <p> Narocilo: <?= $narocilo['id'] ?></p>
                                <p> Vrednost narocila: <?= $narocilo['postavka'] ?> </p>
                                <?php if ($_GET['stanje'] == 'oddano'): ?>
                                <!-- oddana lahko spremeni v - potrjeno ali preklicano -->
                                <form action="<?= BASE_URL . "spremeni-stanje-narocila" ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $narocilo['id'] ?>" />
                                    <input type="hidden" name="novoStanje" value="potrjeno" />
                                    <input type="hidden" name="staroStanje" value="<?= $_GET['stanje'] ?>" />
                                    <input type="submit" value="Potrdi narocilo" />
                                </form>
                                <form action="<?= BASE_URL . "spremeni-stanje-narocila" ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $narocilo['id'] ?>" />
                                    <input type="hidden" name="novoStanje" value="preklicano" />
                                    <input type="hidden" name="staroStanje" value="<?= $_GET['stanje'] ?>" />
                                    <input type="submit" value="Preklici narocilo" />
                                </form>
                                <?php elseif ($_GET['stanje'] == 'potrjeno'): ?>
                                <!-- potrjena lahko samo se stornira -->
                                <form action="<?= BASE_URL . "spremeni-stanje-narocila" ?>" method="post">
                                    <input type="hidden" name="id" value="<?= $narocilo['id'] ?>" />
                                    <input type="hidden" name="novoStanje" value="stornirano" />
                                    <input type="hidden" name="staroStanje" value="<?= $_GET['stanje'] ?>" />
                                    <input type="submit" value="Storniraj narocilo" />
                                </form>
                                
                                <?php else: ?>
                                <!--stornirana in preklicana so toast. Ne moremo vec nic -->      
                                <?php endif ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>