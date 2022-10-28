<?php
session_start();
include "../template/side-navbaruser.php";
include "../template/side-navbaradmin.php";
include "../template/top-navbarNonAuth.php";
include "../template/top-navbarAuth.php";

require_once '../../server/config.php';
require '../../server/users.php';

$user = new Users();
$isAdmin = 0;

if(!isset($_SESSION['username'])){ //Session
    $auth = FALSE;
}else{
    $auth = TRUE;
    $isAdmin = $_SESSION['isAdmin'];
}

if(isset($_COOKIE['cookie_username'])){ //Cookie
    $username = $_COOKIE['cookie_username'];
    $password = $_COOKIE['cookie_password'];
    try{
        $success = $user->login($username, $password);      
    }catch(Exception $e){
        echo "Query Error";
    }
    if($success){
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user->getUserId();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['isAdmin'] = $user->getIsAdmin();
    }
    $auth = TRUE;
    $isAdmin = $_SESSION['isAdmin'];
}

if (isset($_GET['halaman'])){
    $halamanAktif = $_GET['halaman'];
}else{
    $halamanAktif = 1;
}

$totalData = $pdo->prepare("SELECT count(*) FROM album"); 
$totalData->execute();
$countRowTotalData = $totalData->fetch();
$jumlahData = 10;
$dataAwal = ($halamanAktif * $jumlahData) - $jumlahData;

$jumlahPagination = ceil($countRowTotalData[0]/ 10);
$jumlahLink = 3;
if ($halamanAktif < ($jumlahPagination - $jumlahLink)){
    $start_number = $halamanAktif - $jumlahLink;
}else{
    $start_number = 1;
}
if ($halamanAktif < ($jumlahPagination-$jumlahLink)){
    $end_number = $halamanAktif + $jumlahLink;
}else{
    $end_number = $jumlahPagination;
}

$ambilData_perhalaman = $pdo->query("SELECT * FROM album LIMIT $dataAwal,$jumlahData");
$ambilData_perhalaman->execute();
$fetchedData_perhalaman = $ambilData_perhalaman->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/binotify-icon.ico" type="image/x-icon">
    <!-- Style -->
    <link rel="stylesheet" href="../css/daftar-album.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Daftar Album</title>
</head>
<body>
    <?php 
        if($auth){
            topNavbarAuth($_SESSION['username']);
        }else{
            topNavbarNonAuth();
        }
    ?>
    <?php
        if($isAdmin == 1){
            sideNavbarAdmin();
        }else{
            sideNavbarUser();
        }
    ?>
    <div class="containerContent">
        <h1>Album</h1>
            <main>
                <?php
                
                foreach($fetchedData_perhalaman as $row){
                    echo "
                    <div class=\"containerAlbum\" onclick=\"location.href = 'detail-album.php?albumid={$row["album_id"]}';\">
                        <div class=\"cover\">
                            <img src=\"{$row["image_path"]}\" class=\"cover-album\">
                        </div>
                        <div class=\"judul\">
                            <h3>{$row["judul"]}</h3>
                            <span>{$row["penyanyi"]}</span>
                            <a href=\"#\" class=\"fa-solid fa-play\"> </a>
                        </div>
                    </div>
                    ";
                }
                ?>
            </main>
        <div class="pagination">
            <?php 
                if ($halamanAktif > 1){
                    $prevPage = $halamanAktif - 1;
                    echo "<a href=\"?halaman={$prevPage}\" class = \"fa-solid fa-angle-left\"> </a>";
                }
                for ($j = $start_number; $j <= $end_number; $j++){
                    if ($halamanAktif == $j){
                        echo "<a href=\"?halaman={$j}\" style=\"font-weight:bold;\">{$j}</a>";
                    }else{
                        echo "<a href=\"?halaman={$j}\">{$j}</a>";
                    }
                }
                if($halamanAktif < $jumlahPagination){
                    $nextPage = $halamanAktif + 1;
                    echo "<a href=\"?halaman={$nextPage}\" class = \"fa-solid fa-angle-right\"> </a>";
                };
            ?>
        </div>
    </body>
</html>
