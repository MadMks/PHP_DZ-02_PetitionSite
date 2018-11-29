<?php
    require_once('db.conf.php');

	$sql = 'SELECT petitions.*, users.email AS author_email
			FROM petitions
			LEFT JOIN users 
			  ON (petitions.user_id = users.id)
            LEFT JOIN state_of_petitions AS statePetition
              ON (petitions.id = statePetition.petition_id)
            WHERE statePetition.active = 1
			';

	$sth = $dbh->prepare($sql);
	$sth->execute();
	$petitions = $sth->fetchAll(PDO::FETCH_OBJ);
?>

<div class="col-8">
    <?php foreach($petitions as $petition) {?>
        <div><?php echo($petition->title); ?></div>
        <div><?php echo($petition->author_email); ?></div>
        <div><?php echo($petition->count); ?></div>
        <!-- <div>TODO: инфо о петиции</div> -->
        <a href="index.php?page=3&id=<?php echo($petition->id); ?>">
            Описание</a>
        <div>TODO: подписать петицию</div>
        <br><br>
    <?php } ?>
</div>

<div class="col-4">
test col
</div>