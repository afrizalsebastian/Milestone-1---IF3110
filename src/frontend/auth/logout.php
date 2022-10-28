<?php
session_start();
if(isset($_COOKIE['cookie_username'])){
    setcookie('cookie_username','',time()-86400, '/');
    setcookie('cookie_password','', time()-86400, '/');
}
session_destroy();
header("location:login.php");
?>
