<!DOCTYPE html>


<head>
  <title>Trgovina</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="<?= CSS_URL . "style.css" ?>">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
	        <div class="col-md-9">
	            <h1>
	                Vsi izdelki
	            </h1>
	            <br>
					<p>[
			    		<a href="<?= BASE_URL . "izdelki" ?>">Vsi izdelki</a>
			    	]</p>
	            <div class="row">
	            	<?php foreach ($izdelki as $izdelek): ?>
				        <div class="col-md-4">
				            <a href="<?= BASE_URL . "izdelki?id=" . $izdelek["id"] ?>">
				                <?= $izdelek["ime"] ?> (<?= $izdelek["cena"] ?>)
				            </a>
				        </div>
				    <?php endforeach; ?>
	            </div>
	            <ul class="pagination">
	                <li>
	                    <a href="#">Prev</a>
	                </li>
	                <li>
	                    <a href="#">1</a>
	                </li>
	                <li>
	                    <a href="#">2</a>
	                </li>
	                <li>
	                    <a href="#">Next</a>
	                </li>
	            </ul>
	        </div>
	        <div class="col-md-3">
	        	<h1> Tukaj je lahko cart? </h1>
	        </div>
	    </div>
	</div>
</body>