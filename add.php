<?php
    if (!empty($_POST)){
        if (isset($_POST['btnSubmit']) && !empty($_POST['email'])) {
            require('db.conf.php');

            // echo $_POST['title'];
            // echo $_POST['description'];

            // echo($_POST['btnSubmit']);
            

            // Есть ли зарегестрированный автор (по емайл).
            $sth = $dbh->prepare(
                "SELECT * FROM users
                WHERE email = :email"
            );
            $sth->bindValue(':email', $_POST['email']);
            $sth->execute();
            $userEmail = $sth->fetch(PDO::FETCH_ASSOC);
            // print('<br>!$userEmail = '.!$userEmail);
            // print('<br>empty($userEmail) = '.empty($userEmail));
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
            // print '<br>User email id: '.$userEmail['id'];

            // Добавление петиции.
            $sth = $dbh->prepare(
            "INSERT INTO petitions (title, user_id, description)
                      VALUES (:title, :user_id, :description)"
            );
            $sth->bindValue(':title', $_POST['title']);
            $sth->bindValue(':user_id', $userEmail['id']);
            $sth->bindValue(':description', $_POST['description']);

            
            
            $result = $sth->execute();
            if (!$result) {
                echo '<br>'."\nPDO::errorInfo():\n";
                print_r($sth->errorInfo());
            }

            // echo "<br>result=".$result;
        }
        // header('Location: /add.php');
        echo "<script>";
        echo "window.location=document.URL;";
        echo "</script>";
    }

?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<div class="row">
    <div class="col-6">
        <form method="post" action="add.php">

            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Title">
            </div>

            <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control"
                          placeholder="Description"></textarea>
            </div>

            <button class="btn btn-primary" type="submit" 
            name="btnSubmit" value="Add">Add</button>
        </form>
    </div>

    <div class="col-6">
        <?php if ($result){?>
            <div>Петиция добавлена. подтвердите по емайл</div>
        <?php }?>
    </div>

</div>