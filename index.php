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
<div>
	<?php foreach($petitions as $petition) {?>
		<div><?php echo($petition->title); ?></div>
		<div><?php echo($petition->author_email); ?></div>
		<div><?php echo($petition->count); ?></div>
		<br><br>
	<?php } ?>
</div>