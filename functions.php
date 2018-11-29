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
    }

    function AddNewEmail($newEmail){
        global $dbh;

        // Записываем новый емайл в таблицу.
        $sth = $dbh->prepare(
            "INSERT INTO users (email)
                VALUES (:email)"
        );
        $sth->bindValue(':email', $newEmail);
        return $sth->execute();
    }

    function SignThePetition($petitionId, $subsEmail){
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

        if (!IsEmailExists($_POST['subsEmail'])){
            AddNewEmail($_POST['subsEmail']);
        }
        // Получим id пользователя по email.
        $userId = GetEmailId($subsEmail);
        // Закрепляем емайл за петицией.
        ReserveEmailForPetition($petitionId, $userId);
    }

    function SessionUpdate($messageStatus){
        $_SESSION['message'] = $messageStatus;
        echo "<script>";
        echo "window.location=document.URL;";
        echo "</script>";
    }

    function IsAlreadySigned($petitionId, $subsEmail){
        global $dbh;

        // Узнаем есть ли голос этого емайл за данную петицию.
        $sth = $dbh->prepare(
            'SELECT list.*, u.email AS userEmail
            FROM list_of_votes AS list
            LEFT JOIN users AS u
              ON list.user_id = u.id
            WHERE u.email = :subsEmail
            AND list.petition_id = :pId'
        );
        $sth->bindValue(':pId', $petitionId);
        $sth->bindValue(':subsEmail', $subsEmail);
        $sth->execute();
        $votes = $sth->fetch(PDO::FETCH_ASSOC);

        if ($votes['petition_id'] == $petitionId
            && $votes['userEmail'] == $subsEmail){
            return true;
        }
        else{
            false;
        }
    }

    function ReserveEmailForPetition($petitionId, $userId){
        global $dbh;

        $sth = $dbh->prepare(
            'INSERT INTO list_of_votes (user_id, petition_id) 
            VALUE (:userId, :pId)'
        );
        $sth->bindValue(':userId', $userId);
        $sth->bindValue(':pId', $petitionId);
        return $sth->execute();
    }

    function GetEmailId($subsEmail){
        global $dbh;

        $sth = $dbh->prepare(
          'SELECT id FROM users
          WHERE email = :subsEmail'
        );
        $sth->bindValue(':subsEmail', $subsEmail);
        $sth->execute();
        $userId = $sth->fetch(PDO::FETCH_ASSOC);
        return $userId['id'];
    }

    function ActivationOfThePetition($petitionId){
        global $dbh;

        // Активация петиции.
        $sth = $dbh->prepare(
            'UPDATE state_of_petitions
                    SET active = 1
                    WHERE petition_id = :petitionId'
        );
        $sth->bindValue(':petitionId', $petitionId);
        return $sth->execute();
    }
?>