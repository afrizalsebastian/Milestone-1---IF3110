<?php
session_start();
if(isset($_SESSION['username'])){
    header("location:../mainpage/index.php");
}

require_once '../../server/config.php';
require '../../server/users.php';

$user = new Users();
$error_title = "";
$show_error = FALSE;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if($_POST['password'] != $_POST['cpassword']){
        $error_title = "Passoword and Confirm Password Doesn't Match";
        $show_error = TRUE;
    }else{
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        try{
            $newId = $user->register($email, $username, $password);
        }catch (Exception $e){
            $error_title = $e->getMessage();
            $show_error = TRUE;
        }
        if($newId){
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['email'] = $email;
            $_SESSION['isAdmin'] = $user->getIsAdmin();
    
            $cookie_name = 'cookie_username';
            $cookie_value = $username;
            $cookie_time = time() + (86400 * 30 * 14); //2 minggu
            setcookie($cookie_name, $cookie_value, $cookie_time, '/');
    
            $cookie_name = 'cookie_password';
            $cookie_value = $password;
            $cookie_time = time() + (86400 * 30 * 14); //2 minggu
            setcookie($cookie_name, $cookie_value, $cookie_time, '/');
            header("location:../mainpage/index.php");
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
    <link rel="shortcut icon" href="../../assets/binotify-icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/register.css">
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Register</title>
</head>
<body>
    <div class="main-container">
        <div class="container-1">
            <a href="../mainpage/index.php" class="back-arrow"> <i class="fa-solid fa-angle-left"></i><span> Back</span></a>
            <img src="../../assets/binotify-icon.png" alt="">
            <h1> Binotify </h1>
            <p>Hear Your Music</p>
        </div>
        <div class="container-2">
            <div class="navigation">
                <a href="login.php"><span>Login</span></a>
            </div>
            <div class="register-form">
                <h3>Register</h3>
                <p>Welcome! Make Your Account!</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="email">
                        <label for="email">Email</label>
                        <br>
                        <input type="email" name="email" onblur="checkEmailAvailability()" value ="" placeholder="Email" required>
                    </div>
                    <div class="username">
                        <label for="username">Username</label>
                        <br>
                        <input type="text" name="username" onBlur="checkUsernameAvailability()" placeholder="Username" required>
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <br>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="password">
                        <label for="cpassword">Confirm Password</label>
                        <br>
                        <input type="password" name="cpassword" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>

<?php
if ($show_error) {
    echo '<div class="error-container show">';
    echo '<div class="error">';
    echo "<h1>{$error_title}</h1>";
    echo '<button id="close" class="btn-error">OK</button>';
    echo '</div>';
    echo '</div>';
}
?>

<script>
    function checkEmailAvailability(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.querySelector(".email input").className = this.response;
            }
        }
        xhttp.open("POST", "checkAvailability.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var email = document.querySelector(".email input").value;
        xhttp.send("email="+email);
    }

    function checkUsernameAvailability(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.querySelector(".username input").className = this.response;
            }
        }
        xhttp.open("POST", "checkAvailability.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var usern = document.querySelector(".username input").value;
        xhttp.send("usern="+usern);
    }

    const errorContainer = document.querySelector(".error-container");
    const btnClose = document.querySelector("#close");

    btnClose.addEventListener('click', () =>{
        errorContainer.classList.remove('show');
    })
</script>
</html>