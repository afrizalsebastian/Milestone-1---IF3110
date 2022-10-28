<?php
session_start();
include "../template/side-navbaruser.php";
include "../template/side-navbaradmin.php";
include "../template/top-navbarNonAuth.php";
include "../template/top-navbarAuth.php";

require_once '../../server/config.php';
require '../../server/users.php';


$song_pdo1 = $pdo->query("SELECT * FROM songs WHERE song_id = {$_GET['id']}");
$song_pdo1->execute();

$song = $song_pdo1->fetch();


$song_pdo2 = $pdo->query("SELECT * FROM songs");
$song_pdo2->execute();

$song_data = $song_pdo2->fetchAll(); 

/**
 * index to 
 */

$user = new Users();
$index = 0;
$found = false;
while($index < count($song_data) && !$found){
    if($song_data[$index]['song_id'] == $_GET['id']) {
        $found = true;
    } else {
        ++$index;
    };
}

function prevSong($currentIndex) {
    if($currentIndex > 0 ) {
        return $currentIndex --;
    }
}

function nextSong() {
    if($index < $count($song_data) ) {
        return $index;
    }
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/binotify-icon.ico" type="image/x-icon">
    <!-- Style -->
    <link rel="stylesheet" href="../css/detail-lagu.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Home</title>
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
            <?php
            $path_lagu = $song_data[($index > 0 ? ($index-1):0)]['image_path'];
            $judul = $song_data[($index > 0 ? ($index-1):0)]['judul'];
            $penyanyi = $song_data[($index > 0 ? ($index-1):0)]['penyanyi'];
            $tanggal = $song_data[($index > 0 ? ($index-1):0)]['tanggal_terbit'];
            $genre = $song_data[($index > 0 ? ($index-1):0)]['genre'];
            ?>
            <div class="album-cover">
                <img src="<?=$path_lagu?>" alt="">
            </div>
            <div class="album-title"><?=$judul?></div>
            <div class="senTitle"><?=$penyanyi?></div>
        </div>
        <div class="jumbotron">
            <h1>Details :</h1>
            <h2>Tanggal Terbit : <?=$tanggal?></h1>
            <h2>Genre : <?=$genre?></h1>
        </div>
    </div>

    <div class="bawah">
        <div class="bottom">
                <input type="range" name="range" id="progressBar" min="0" max="100" value="0">
                <div>
                    <span id="durasi"></span>
                    <a href="detail-lagu.php?id=<?= $song_data[($index > 0 ? ($index-1):0)]['song_id'] ?>"><i class="fa fa-solid fa-backward" id="prevSong"></i></a>
                    <i class="fa fa-regular fa-circle-play" id="masterPlay"></i>
                    <a href="detail-lagu.php?id=<?= $song_data[($index < count($song_data)-1 ? ($index+1):(count($song_data)-1))]['song_id'] ?>"><i class="fa fa-solid fa-forward"></i></a>
                </div>
                <div class="songInfo">
                    <img src="https://i.gifer.com/Z23b.gif" width="32" id="gif"  alt="">
                </div>
        </div>
    </div>
</body>
</html>

<script>
    let prevSong = document.getElementById('prevSong');
    let masterPlay = document.getElementById('masterPlay');
    let audio1 = new Audio('../../assets/songs/<?= $song_data[$index]['audio_path'] ?>');
    let progresBar = document.getElementById('progressBar');
    let gif =  document.getElementById('gif');
    let progres = 0;
    let songIndex =  document.getElementById('songId');


    masterPlay.addEventListener('click' ,()=>{
        if(audio1.paused || audio1.currentTime<=0){
            console.log('play');
            audio1.play();
            masterPlay.classList.remove('fa-circle-play');
            masterPlay.classList.add('fa-circle-pause');
            masterPlay.classList.add(audio1.currentTime);
            gif.style.opacity = 1;
            console.log(songIndex);
            
        }
        else{
            audio1.pause();
            masterPlay.classList.remove('fa-circle-pause');
            masterPlay.classList.add('fa-circle-play');
            gif.style.opacity = 0;
        }
        
    })

    audio1.addEventListener('timeupdate', ()=>{
        progres = parseInt((audio1.currentTime/audio1.duration)*100);
        progresBar.value = progres;
        console.log(progres);
        document.getElementById('durasi').innerHTML=progres;
        
    })
    progresBar.addEveantListener('change',()=>{
        temp = (progressBar.value * audio1.duration)/100;
        audio1.currentTime = temp;
    })

</script>
