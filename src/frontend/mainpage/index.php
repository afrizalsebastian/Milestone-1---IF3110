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

$song_pdo = $pdo->query("SELECT * FROM songs");
$song_pdo->execute();
$song_data = $song_pdo->fetchAll();

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

$totalData = $pdo->prepare("SELECT count(*) FROM songs"); 
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

$ambilData_perhalaman = $pdo->query("SELECT * FROM songs LIMIT $dataAwal,$jumlahData");
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
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Binotify</title>
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
        <h1>Song</h1>
        <div class="containerHeader">
            <div class="itemHeader1">#</div>
            <div class="itemHeader2">TITLE</div>
            <div class="itemHeader3">ALBUM</div>
            <div class="itemHeader4">DURATION</div>
        </div>
        <?php
            $i = $dataAwal + 1;
            foreach($fetchedData_perhalaman as $song){
                echo "<a href=\"detail-lagu.php?id=" . "{$song['song_id']}\">";
                $ambilAlbum = $pdo->prepare("SELECT judul FROM album WHERE album_id = {$song["album_id"]}");
                $ambilAlbum->execute();
                $fetchAlbum = $ambilAlbum->fetch();
                $judulAlbum = $fetchAlbum["judul"];

                $menit = floor($song["duration"]/60);
                $detik = $song["duration"] - $menit*60;
                if ($menit < 10){
                    if ($detik <10){
                        $durasi = "0{$menit}:0{$detik}";
                    }else{
                        $durasi = "0{$menit}:{$detik}";
                    }
                }else{
                    if ($detik <10){
                        $durasi = "{$menit}:0{$detik}";
                    }else{
                        $durasi = "{$menit}:{$detik}";
                    }
                }
                echo"
                <div class=\"containerItem\">
                    <div class=\"no\">
                    $i
                    </div>
                    <div class=\"cover\">
                        <img class= 'song_img' src=\"{$song["image_path"]}\" width=\"100px\" class=\"cover-album\">
                    </div>
                    <div class=\"title\">
                        {$song["judul"]}
                    </div>
                    <div class=\"singer\">
                        {$song["penyanyi"]}
                    </div>
                    <div class=\"album\">
                        {$judulAlbum}
                    </div>
                    <div class=\"duration\">
                        {$durasi}
                    </div>
                </div>
                ";
                $i = $i + 1;
                echo "</a>";
            }
        ?>
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
    </div>
</body>
<!--

-->
</html>
