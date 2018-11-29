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
                // Активация петиции.
                $sth = $dbh->prepare(
                    'UPDATE state_of_petitions
                    SET active = 1
                    WHERE petition_id = :petitionId'
                );
                $sth->bindValue(':petitionId', $petitionId);
                $sth->execute();
            }
        }
    }
?>
