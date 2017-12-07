<!DOCTYPE html>

<head>
	<title>Dodaj nov izdelek</title>
	<link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
</head>



<body>
<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills">
					<li class="active">
						 <a href="<?= BASE_URL . "izdelki" ?>""> <span class="badge pull-right"></span> Nazaj</a>
					</li>
				</ul>
				<div class="row">
					<div class="col-md-8">
                                            <br>
						<form action="<?= BASE_URL . "izdelki/add" ?>" method="post">
                                                    <p><label>Ime: <input type="text" name="ime" value="<?= $ime ?>" autofocus /></label></p>
                                                    <p><label>Cena: <input type="number" step="0.01" min="0" name="cena" value="<?= $cena ?>" /></label></p>
                                                    <p><label>Opis: <input type="text" name="opis" value="<?= $opis ?>" /></label></p>
                                                    <p><button>Insert</button></p>
                                                </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
    
</body>
</html>