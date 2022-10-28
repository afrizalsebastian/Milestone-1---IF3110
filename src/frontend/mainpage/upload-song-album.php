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

require_once '../../server/config.php';

$messageSub = "";
$show_message = FALSE;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if($_POST['submit'] == "song-submit"){
        $song_target_dir = "../../assets/songs/";
        $img_target_dir = "../../assets/img_song/";
        $song_target_file = $song_target_dir . basename($_FILES["audio"]["name"]);
        $img_target_file = $img_target_dir . basename($_FILES['image']['name']);
        $fileUploadOk = 1;
        $songsFileType = strtolower(pathinfo($song_target_file,PATHINFO_EXTENSION));
        $imageFileType = strtolower(pathinfo($img_target_file,PATHINFO_EXTENSION));

        
        
        if(file_exists($song_target_file) || file_exists($img_target_file)){
            $messageSub = "File already Exists";
            $fileUploadOk = 0;
            $show_message = TRUE;
        }
        
        
        if($songsFileType != "mp3" && $songsFileType != "ogg"){
            $messageSub = "Songs only .mp3 or .ogg File";
            $fileUploadOk = 0;
            $show_message = TRUE;
        }
        

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $messageSub = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $fileUploadOk = 0;
            $show_message = TRUE;
        }
        
        $album_id = NULL;

        if($_POST['albumname'] != NULL){
            $queryAlbum = "SELECT album_id FROM binotify.album WHERE(judul = :judul)";
            $albValue = array(':judul' => $_POST['albumname']);
            $resAlb = $pdo->prepare($queryAlbum);
            $resAlb->execute($albValue);
            $rowAlb = $resAlb->fetch();
            $lenRow = count($rowAlb);

            if($lenRow > 0){
                $album_id = $rowAlb['album_id'];
            }else{
                $messageSub = "Album Not Exists";
                $fileUploadOk = 0;
                $show_message = TRUE;
            }
        }

        if($fileUploadOk != 0){
            $title = $_POST['songtitle'];
            $date = $_POST['realesedate'];
            $singer = $_POST['singer'];
            $genre = $_POST['genre'];
            $duration = $_POST['duration'];
            $intDuration = (int) $duration;
            
            $query = "INSERT INTO binotify.songs (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) VALUES (:judul, :penyanyi, :terbit, :genre, :duration, :audio_path, :img, :albid)";
            
            $value = array(':judul' => $title, ':penyanyi'=>$singer, ':terbit' =>$date, ':genre'=> $genre, ':duration'=>$intDuration, ':audio_path'=>$song_target_file, ':img'=>$img_target_file, ':albid' => $album_id);

            try{
                $res = $pdo->prepare($query);
                $res->execute($value);
                
                move_uploaded_file($_FILES['audio']['tmp_name'], $song_target_file);
                move_uploaded_file($_FILES['image']['tmp_name'],$img_target_file);
                $messageSub = "Add Song Successfully";
                $show_message = TRUE;
            }catch (Exception $e){
                $messageSub = "Query Error";
                $show_message = TRUE;
            }
        }
    }

    if($_POST['submit'] == 'album-submit'){
        $img_target_dir = "../../assets/img_album/";
        $img_target_file = $img_target_dir . basename($_FILES['image']['name']);
        $fileUploadOk = 1;
        $imageFileType = strtolower(pathinfo($img_target_file,PATHINFO_EXTENSION));

        if(file_exists($img_target_file)){
            $messageSub = "File already Exists";
            $fileUploadOk = 0;
            $show_message = TRUE;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $messageSub = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $fileUploadOk = 0;
            $show_message = TRUE;
        }

        if($fileUploadOk != 0){
            $title = $_POST['songtitle'];
            $date = $_POST['realesedate'];
            $singer = $_POST['singer'];
            $genre = $_POST['genre'];
            
            $query = "INSERT INTO binotify.album (judul, penyanyi, image_path, tanggal_terbit, genre) VALUES (:judul, :penyanyi, :img, :terbit, :genre)";
            $value = array(':judul' => $title, ':penyanyi'=>$singer, ':img'=>$img_target_file,':terbit' =>$date, ':genre'=> $genre);
            try{
                $res = $pdo->prepare($query);
                $res->execute($value);
                
                move_uploaded_file($_FILES['image']['tmp_name'],$img_target_file);
                $messageSub = "Add Album Successfully";
                $show_message = TRUE;
            }catch (Exception $e){
                $messageSub = "Query Error";
                $show_message = TRUE;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/binotify-icon.ico" type="image/x-icon">
    <!-- Style -->
    <link rel="stylesheet" href="../css/upload-song-album.css">
    <link rel="stylesheet" href="../css/side-navbar.css">
    <link rel="stylesheet" href="../css/top-navbar.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Uploader</title>
</head>
<body>
    <?php topNavbarAuth($_SESSION['username']);?>
    <?php sideNavbarAdmin();?>

    <div class="main-container">
        <div class="uploader">
            <h1>Uploader</h1>
            <div class="song-uploader">
                <h3>Songs Uploader</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="s-keyb">
                        <input type="text" class="key-input" name="songtitle" placeholder="Song Title" required>
                        <input type="date" class="key-input date-in" name="realesedate" required>
                        <input type="text" class="key-input" name="singer" placeholder="Singer" required>
                        <input type="text" class="key-input" name="albumname" placeholder="Album Name">
                        <input type="text" class="key-input" name="genre" placeholder="Genre">
                    </div>
                    <div class="s-file">
                        <label for="audio">Uploud Song</label>
                        <input type="file" class="file-input" id="sg" name="audio" required>
                        <input type="hidden" id="dur" name="duration" value="">
                        <label for="image">Uploud Image</label>
                        <input type="file" class="file-input" name="image" required>
                    </div>
                    <button type="submit" name="submit" value="song-submit">Uploud</button>
                </form>
                <audio id='audio' style="display: hidden;"></audio>
            </div>
            <div class="album-uploader" >
                <h3>Album Uploader</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="s-keyb">
                        <input type="text" class="key-input" name="songtitle" placeholder="Album Title" required>
                        <input type="date" class="key-input date-in" name="realesedate" required>
                        <input type="text" class="key-input" name="singer" placeholder="Singer" required>
                        <input type="text" class="key-input" name="genre" placeholder="Genre">
                    </div>
                    <div class="s-file">
                        <label for="image">Uploud Image</label>
                        <input type="file" class="file-input" name="image" required>
                    </div>
                    <button type="submit" name="submit" value="album-submit">Uploud</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    if ($show_message) {
        echo '<div class="message-container show">';
        echo '<div class="message">';
        echo "<h1>{$messageSub}</h1>";
        echo '<button id="close" class="btn-message">OK</button>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <script>
        var music_duration = 0;
        document.getElementById('audio').addEventListener('canplaythrough', function(e){
            music_duration = Math.round(e.currentTarget.duration);
            document.getElementById('dur').value = music_duration;
            URL.revokeObjectURL(obUrl);
        })

        var obUrl;
        document.getElementById("sg").addEventListener('change', function(e){
            var file = e.currentTarget.files[0];

            if(file.name.match(/\.(mp3|ogg)$/i)){
            obUrl = URL.createObjectURL(file);
            document.getElementById('audio').setAttribute('src', obUrl);
        }
        })

        const messageContainer = document.querySelector(".message-container");
        const btnClose = document.querySelector("#close");

        btnClose.addEventListener('click', () =>{
            messageContainer.classList.remove('show');
        })
    </script>
</body>
</html>