<?php
	require_once('db.conf.php');

	$sql = 'SELECT petitions.*, users.email AS author_email
			FROM petitions
			LEFT JOIN users 
			ON (petitions.user_id = users.id)
			';

	$sth = $dbh->prepare($sql);
	$sth->execute();
	$petitions = $sth->fetchAll(PDO::FETCH_OBJ);

	// print_r($petitions);	
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="style.css">

	<title>Document</title>
</head>
<body>

	<div class="container">
		<div class="row">

			<div class="col-8">
				<?php foreach($petitions as $petition) {?>
					<div><?php echo($petition->title); ?></div>
					<div><?php echo($petition->author_email); ?></div>
					<div><?php echo($petition->count); ?></div>
					<div>TODO: инфо о петиции</div>
					<div>TODO: подписать петицию</div>
					<br><br>
				<?php } ?>
			</div>

			<div class="col-4">
			test col
			</div>

		</div>
	</div>
	

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.js"></script>
  

</body>
</html>