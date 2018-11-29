<?php
    if (isset($_GET['id'])) {

        include_once ("db.conf.php");

        $petitionId = $_GET['id'];

        $sth = $dbh->prepare(
            'SELECT * FROM petitions AS p
            LEFT JOIN users
              ON p.user_id = users.id
            WHERE p.id = :petitionId'
        );
        $sth->bindValue(':petitionId', $petitionId);
        $sth->execute();
        $petition = $sth->fetch(PDO::FETCH_OBJ);

        if (!empty($petition)){
            ShowPetition($petition);
        }
        else{
            ShowNotPetition();
        }
    }
?>

<?php
    function ShowPetition($petition){
?>
        <div class="col-8">
            <h1>
                <?php echo($petition->title); ?>
            </h1>
            <p>
                <?php echo($petition->description); ?>
            </p>
        </div>

        <div class="col-4">
            TODO подписать петицию.
        </div>
<?php
    }

    function ShowNotPetition(){
?>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                Петиции с таким id не существует!
            </div>
        </div>
<?php
    }
?>

