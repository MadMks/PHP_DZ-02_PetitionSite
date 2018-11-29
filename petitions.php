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
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?php echo($petition->title); ?></h5>
            <div class="card-subtitle">Автор: <?php echo($petition->author_email); ?></div>
            <div class="card-subtitle">Подписей: <?php echo($petition->countOfVotes); ?></div>
            <a href="index.php?page=3&id=<?php echo($petition->id); ?>">
                Описание</a>
        </div>
    </div>
    <?php } ?>

</div>

<div class="col-4">
    <div class="card">
        <div class="card-body">
            <div class="card-text">
                Количество петиций: <?php echo count($petitions); ?>
            </div>
        </div>
    </div>
</div>