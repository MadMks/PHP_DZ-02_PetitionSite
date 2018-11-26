<?php
	require_once('db.conf.php');

	$sql = 'SELECT petitions.*, users.email AS author_email,
			petitions
			FROM petitions
			LEFT JOIN users 
			ON (petitions.user_id = users.id)
			';

	$sth = $dbh->prepare($sql);
	$sth->execute();
	$petitions = $sth->fetchAll(PDO::FETCH_OBJ);

	print_r($petitions);	

	foreach ($petitions as $petition) {
		echo($petition);
	}
?>
<div>

</div>