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

	print_r($petitions);	

	foreach ($petitions as $petition) {
		echo("<br>");
		echo($petition->title);
	}
?>
<div>
	<?php foreach($petitions as $petition) {?>
		<div><?php $petition->title ?></div>
	<?php } ?>
</div>