<?php

require_once '../../server/config.php';

if(!empty($_POST['email'])){
    $email = $_POST['email'];
    $query = "SELECT email FROM binotify.users WHERE (email = :email)";
    $value = array(':email' => $email);

    $res = $pdo->prepare($query);
    $res->execute($value);

    $row = $res->fetchAll(PDO::FETCH_OBJ);

    if($res-> rowCount() > 0){
        echo "non-avail";
    }else{
        echo "avail";
    }
}

if(!empty($_POST['usern'])){
    $usern = $_POST['usern'];
    $query = "SELECT username FROM binotify.users WHERE (username = :username)";
    $value = array(':username' => $usern);

    $res = $pdo->prepare($query);
    $res->execute($value);

    $row = $res->fetchAll(PDO::FETCH_OBJ);

    if($res-> rowCount() > 0){
        echo "non-avail";
    }else{
        echo "avail";
    }
}

?>