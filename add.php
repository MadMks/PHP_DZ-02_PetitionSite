<?php
    if (!empty($_POST)){
        if (isset($_POST['btnSubmit']) 
        && !empty($_POST['btnSubmit'])
            && !empty($_POST['title'])
            && !empty($_POST['email'])
            && !empty($_POST['description'])
            ) {
            require('db.conf.php');

            

            // Есть ли зарегестрированный автор (по емайл).
            $sth = $dbh->prepare(
                "SELECT * FROM users
                WHERE email = :email"
            );
            $sth->bindValue(':email', $_POST['email']);
            $sth->execute();
            $userEmail = $sth->fetch(PDO::FETCH_ASSOC);
            
            if (empty($userEmail)) {
                // Добавление нового автора (емайл).
                $sth = $dbh->prepare(
                    "INSERT INTO users (email)
                    VALUES (:email)"
                );
                $sth->bindValue(':email', $_POST['email']);
                $sth->execute();
                // новый запрос для получения id user email
                $sth = $dbh->prepare(
                    "SELECT * FROM users
                    WHERE email = :email"
                );
                $sth->bindValue(':email', $_POST['email']);

                $sth->execute();
                $userEmail = $sth->fetch(PDO::FETCH_ASSOC);
            }

            // Добавление петиции.
            $sth = $dbh->prepare(
            "INSERT INTO petitions (title, user_id, description)
                      VALUES (:title, :user_id, :description)"
            );
            $sth->bindValue(':title', $_POST['title']);
            $sth->bindValue(':user_id', $userEmail['id']);
            $sth->bindValue(':description', $_POST['description']);

            $sth->execute();

            // Получение id петиции.
            $sth = $dbh->prepare(
                "SELECT * FROM petitions
                WHERE title = :title
                AND user_id = :user_id"
            );
            $sth->bindValue(':title', $_POST['title']);
            $sth->bindValue(':user_id', $userEmail['id']);
            $sth->execute();

            $petition = $sth->fetch(PDO::FETCH_ASSOC);

            // Добавление петиции в таблицу состояний.
            $sth = $dbh->prepare(
                "INSERT INTO state_of_petitions 
                    (user_id, petition_id, activationKey)
                VALUES (:user_id, :petition_id, :activationKey)"
            );
            $token = uniqid();
            $sth->bindValue(':user_id', $userEmail['id']);
            $sth->bindValue(':petition_id', $petition['id']);
            $sth->bindValue(':activationKey', $token);
            $result = $sth->execute();

            if ($result) {
                sendMail($userEmail['email'], $petition['id'], $token);
            }

            $_SESSION['message'] = 'success';
        }

        // header('Location: /add.php');
        echo "<script>";
        echo "window.location=document.URL;";
        echo "</script>";
    }
    else {
        // Вывод формы.
        if (!empty($_SESSION['message'])) {
            ShowFormAdd();
            ShowPetitionSuccess();
            unset($_SESSION['message']);
        }
        else {
            ShowFormAdd();
        }
    }
?>


<?php 
    function ShowFormAdd(){
?>
    <div class="col-6">
        <form method="post">

            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Название">
            </div>

            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control"
                          placeholder="Описание"></textarea>
            </div>

            <button class="btn btn-primary" type="submit" 
            name="btnSubmit" value="Add">Добавить</button>

        </form>
    </div>
<?php  
    }

    function ShowPetitionSuccess(){
?>
    <div class="col-6">
        <div class="alert alert-success">
            На почту отправлено письмо для подтверждения...
        </div>
    </div>
<?php 
    }


    function sendMail($email, $petitiionId, $token){

        $message = 'Перейдите по'
            .'<a href="http://localhost:81/index.php'
            .'?page=4'
            ."&id=$petitiionId&token=$token"
            .'">'
            .'ссылке</a>,'
            .'для активации петиции.';

        if (mail(
            $email, 
            "Подтверждение петиции", 
            $message,
            "From: info@max.itstep.fun \r\n"))
        { 
            // echo "сообщение успешно отправлено"; 
        } else { 
            // echo "при отправке сообщения возникли ошибки"; 
        }
    }
?>

