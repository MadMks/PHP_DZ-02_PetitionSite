<?php

    // require_once('db.conf.php');

    function IsEmailExists($email){
        global $dbh;
        // Есть ли зарегестрированный автор (по емайл).
        $sth = $dbh->prepare(
            "SELECT * FROM users
            WHERE email = :email"
        );
        $sth->bindValue(':email', $email);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);


//        if (empty($userEmail)) {
//
//            $result = AddNewEmail($email);
//
//            if ($result) {
//                return true;
//            }
//            else{
//                return false;
//            }
//        }
//        else{
//            return true;
//        }
    }

    function AddNewEmail($newEmail){
        global $dbh;

        // Записываем новый емайл в таблицу.
        $sth = $dbh->prepare(
            "INSERT INTO users (email)
                VALUES (:email)"
        );
        $sth->bindValue(':email', $newEmail);
        $result = $sth->execute();

        if ($result) {
            return true;
        }
        else{
            return false;
        }
    }

    function SignThePetition($petitionId){
        global $dbh;

        // Петиция за которую голосуем.
        $sth = $dbh->prepare(
            "SELECT * FROM petitions AS p
            WHERE p.id = :pId"
        );
        $sth->bindValue(':pId', $petitionId);
        $sth->execute();
        $petition = $sth->fetch(PDO::FETCH_ASSOC);

        $newCount = $petition['countOfVotes'] + 1;

        // Увеличиваем кол-во голосов на 1.
        $sth = $dbh->prepare(
            'UPDATE petitions AS p
             SET p.countOfVotes = :newCount
             WHERE p.id = :pId'
        );
        $sth->bindValue(':pId', $petitionId);
        $sth->bindValue(':newCount', $newCount);
        $sth->execute();

        // Закрепляем емайл за петицией.
        // TODO: Закрепляем емайл за петицией.
    }

    function SessionUpdate($messageStatus){
        $_SESSION['message'] = $messageStatus;
        echo "<script>";
        echo "window.location=document.URL;";
        echo "</script>";
    }
?>