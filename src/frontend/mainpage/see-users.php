<?php
session_start();
include "../template/side-navbaradmin.php";
include "../template/top-navbarAuth.php";

if(!isset($_SESSION['username'])){
    header("location:index.php");
}else{
    if($_SESSION['isAdmin'] == 0){
        header("location:index.php");
    }
}

require_once "../../server/config.php";

$query = "SELECT * FROM binotify.users";
$res = $pdo->prepare($query);
$res->execute();
$rows = $res->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/binotify-icon.ico" type="image/x-icon">
    <!-- Style -->
    <link rel="stylesheet" href="../css/see-users.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>All Users</title>
</head>
<body>
    <?php topNavbarAuth($_SESSION['username']);?>
    <?php sideNavbarAdmin();?>

    <div class="main-container">
        <h1 class="all-user-title">All Users</h1>
        <div class="name-table-container">
            <div class="table-header1">User ID</div>
            <div class="table-header2">Username</div>
            <div class="table-header3">Email Adrress</div>
            <div class="table-header4">isAmin</div>
        </div>
        <div class="see-users">
            <?php foreach($rows as $row):?>
                <div class="users-value">
                    <div class="user-id"><?php echo $row['user_id']?></div>
                    <div class="user-name"><?php echo $row['username']?></div>
                    <div class="email-add"><?php echo $row['email']?></div>
                    <div class="is-admin">
                        <?php if($row['isAdmin']):?>
                            True
                        <?php else :?>
                            False
                        <?php endif?>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
</body>
</html>