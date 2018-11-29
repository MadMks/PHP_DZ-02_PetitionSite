<?php
    if (isset($_GET['id']) && isset($_GET['token'])) {
        
        require_once('db.conf.php');

        $petitionId = $_GET['id'];
        $token = $_GET['token'];

        $sth = $dbh->prepare(
            "SELECT * FROM state_of_petitions
            WHERE petition_id = :petitionId"
        );
        $sth->bindValue(':petitionId', $petitionId);
        $sth->execute();
        $petitionState = $sth->fetch(PDO::FETCH_ASSOC);

        if (!empty($petitionState)) {
            if ($petitionState['activationKey'] == $token) {

                if (ActivationOfThePetition($petitionId)){
//                    SessionUpdate('success');
                    ?>
                    <div class="alert alert-success mt-3">
                        Петиция подтверждена.
                    </div>
                    <?php
                }
                else{
                    header('Location: index.php?page=1');
                }
            }
            else{
                ?>
                <div class="alert alert-warning mt-3">
                    Неверный токен.
                </div>
                <?php
            }
        }
    }
?>


