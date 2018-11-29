<?php
session_start();
include_once ("functions.php");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- <link rel="stylesheet" href="style.css"> -->

	<title>Document</title>
</head>
<body>

	<header class="navbar navbar-light bg-light">
		<div class="container">
			<div class="row">
				<nav class="col-12">
					<?php 
						include_once('menu.php');
					?>
				</nav>
			</div>
		</div>
	</header>

	<main class="mt-5">
		<div class="container">
			<div class="row">
				<?php
					if(isset($_GET['page']))
					{
						if($_GET['page']==1) include_once("petitions.php");
						if($_GET['page']==2) include_once("add.php");
						if($_GET['page']==3) include_once("petition.php");
                        if($_GET['page']==4) include_once("activation.php");
					}
					else{
						include_once("petitions.php");
					}
				?>
			</div>
		</div>
	</main>
	

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.js"></script>
  

</body>
</html>