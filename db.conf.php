<?php
    try{
        $dbh = new PDO('mysql:host=localhost;dbname=petitions', 'root', '');
    }
    catch(PDOException $e){
        echo($e->getMessage());
        exit();
    }