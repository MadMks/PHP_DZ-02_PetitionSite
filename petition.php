<?php
    include_once ("db.conf.php");
    include_once("functions.php");

    if (isset($_GET['id'])) {

//        include_once ("db.conf.php");

        $petitionId = $_GET['id'];

        $sth = $dbh->prepare(
            'SELECT p.*, users.email AS author 
            FROM petitions AS p
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

    // Подписываем петицию.
    if (isset($_POST['btnSubmit'])
        && !empty($_POST['subsPetitionId'])
        && !empty($_POST['subsEmail'])) {

        if (!IsEmailExists($_POST['subsEmail'])){

            if (AddNewEmail($_POST['subsEmail'])){
                SignThePetition($_POST['subsPetitionId']);
            }

            SessionUpdate('success');
        }
        else{
            SessionUpdate('exists');
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
            <p class="text-justify">
                <?php echo($petition->description); ?>
            </p>
            <div>
                Автор: <?php echo($petition->author); ?>
            </div>
            <div>
                Подписей: <?php echo($petition->countOfVotes); ?>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">Подписать петицию</h5>

                    <form method="POST">
                        <div class="form-group">
                            <input name="subsEmail" type="email" class="form-control" 
                                    placeholder="Введите email">
                        </div>

                        <input type="hidden" name="subsPetitionId"
                                value="<?php echo($petition->id); ?>">

                        <button name="btnSubmit" type="submit" 
                                class="btn btn-primary">
                            Подписать</button>
                    </form>

                    <?php ShowSubscribeStatus(); ?>

                </div>
            </div>
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

    function ShowSubscribeStatus(){
        if (!empty($_SESSION['message'])) {
            if ($_SESSION['message'] == 'success') {
                ?>
                <div class="alert alert-success mt-3">
                    Ваша подпись учтена.
                </div>
                <?php
            }
            if ($_SESSION['message'] == 'exists') {
                ?>
                <div class="alert alert-warning mt-3">
                    Вы уже голосовали.
                </div>
                <?php
            }
            unset($_SESSION['message']);
        }
    }
?>

