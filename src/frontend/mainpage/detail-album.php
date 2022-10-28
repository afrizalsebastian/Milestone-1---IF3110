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


$idAlbum = (int)$_GET['albumid'];


// Dapatkan Semua Album
$queryAlbum = "SELECT * FROM binotify.album where (album_id = :album_id)";
$valueAlbum = array(':album_id' => $idAlbum);
$resAlbum = $pdo->prepare($queryAlbum);
$resAlbum->execute($valueAlbum);

$rowAlbum = $resAlbum->fetch();

//Dapatkan Music
$queryMusic = "SELECT * FROM binotify.songs where (album_id = :album_id)";
$valueMusic = array(':album_id' => $idAlbum);
$resMusic = $pdo->prepare($queryMusic);
$resMusic->execute($valueMusic);

$rowMusic = $resMusic->fetchAll();
$countMusic = count($rowMusic);

$numbering = 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/binotify-icon.ico" type="image/x-icon">
    <!-- Style -->
    <link rel="stylesheet" href="../css/detail-album.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Detail Album</title>
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

    <div class="main-container">
        <div class="jumbotron">
            <div class="album-cover">
                <img src="<?php echo $rowAlbum['image_path']?>" alt="">
            </div>
            <div class="sen-album">ALBUM</div>
            <div class="album-title"><?php echo $rowAlbum['judul']?></div>
            <div class="det-album"><?php echo $rowAlbum['penyanyi']?>, <?php echo $rowAlbum['tanggal_terbit']?>, <?php echo $countMusic?>, <?php echo floor($rowAlbum['total_duration']/60)?>:<?php echo $rowAlbum['total_duration']%60?></div>
        </div>
        <div class="table-name">
            <div class="tab-name-1">#</div>
            <div class="tab-name-2">JUDUL</div>
            <div class="tab-name-3"><i class="fa-solid fa-clock"></i></div>
        </div>
        <div class="all-song-container">
            <?php foreach($rowMusic as $music):?>
                <div class="song-container">
                    <div class="number"><?php echo $numbering?></div>
                    <div class="song-title"><?php echo $music['judul']?></div>
                    <div class="singer"><?php echo $music['penyanyi']?></div>
                    <div class="duration"><?php echo floor($music['duration']/60)?>:<?php echo $music['duration']%60?></div>
                </div>
            <?php $numbering += 1?>
            <?php endforeach;?>
        </div>
    </div>
</body>
</html>
