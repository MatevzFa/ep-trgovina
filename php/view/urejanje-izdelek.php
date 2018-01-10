<!DOCTYPE html>

<html>

    <head>
        <title>Urejanje izdelka</title>
        <?php include(HEAD); ?>
    </head>

    <body>
        <?php include(NAVBAR); ?>
        <div class="container-fluid">
            <div class="row">
                <div class="container col-md-6 border border-top-0 border-bottom-0 border-left-0">
                    <a href="<?= BASE_URL . "prikaz-izdelkov-cmp" ?>""> 
                        Nazaj na vse izdelke
                    </a>
                    <h1> Urejanje podatkov izdelka </h1>
                    <?php if (isset($_SESSION['user_id'])):?>
                        <form action="<?= BASE_URL . "urejanje-izdelka" ?>" method="POST">
                            Ime izdelka: <input type="text" name="ime" value="<?= $izdelek['ime'] ?>"><br>
                            Opis izdelka: <textarea type="text" rows="4" cols="50" name="opis"><?= $izdelek['opis']?></textarea><br>
                            Cena izdelka: <input type="number" step="0.01" min="0" name="cena" value="<?= $izdelek['cena']?>"><br>
                            <input type="hidden" name="id" value="<?= $izdelek['id'] ?>" />
                            <input type="submit" value="Uredi izdelek">
                        </form>
                </div>
                <div class="col-md-6 p-0">
                    <h2> Dodajanje nove slike: </h2>
                    <form action="<?= BASE_URL . "dodaj-sliko-izdelku" ?>" method="POST" enctype="multipart/form-data" >
                        Slika izdelka: <input accept="image/*" type="file" name="slika"> <br><br>
                        <input type="hidden" name="izdelek_id" value="<?= $izdelek['id'] ?>" />    
                        <input type="submit" value="Dodaj sliko">
                    </form>
                    <br><br>
                    <h2> Brisanje slik: </h2>
                    <?php foreach ($slike as $slika): ?>
                        <div class="izdelek card" style="width: 20rem;">
                            <img class="card-img-top" style="width: 20rem;"
                                 src="<?= IMAGES_URL . $slika['path'] ?>">
                            <div class="card-body">
                            <form action="<?= BASE_URL . "izbrisi-sliko" ?>" method="POST">
                                <input type="hidden" name="izdelek_id" value="<?= $izdelek['id'] ?>" />  
                                <input type="hidden" name="id" value="<?= $slika['id'] ?>" />
                                <input type="hidden" name="slika" value="<?= $slika['path'] ?>" />
                                <button type="submit" class="btn btn-danger">Izbrisi sliko</button>
                            </form>
                            </div>                                
                        </div>
                        
                        
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
       <?php else: ?>
           <a href="<?= BASE_URL . "prijava" ?>">Prijavite se</a>
       <?php endif; ?>
    </body>

</html>