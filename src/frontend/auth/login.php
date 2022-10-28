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
    $username = $_POST['username'];
    $password = $_POST['password'];
    try{
        $success = $user->login($username, $password);      
    }catch(Exception $e){
        $error_title = $e->getMessage();
        $show_error = TRUE;
    }

    if($success){
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user->getUserId();
        $_SESSION['email'] = $user->getEmail();
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
    }else{
        $error_title = "Authentication Failed";
        $show_error = TRUE;
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
    <link rel="stylesheet" href="../css/login.css">
    <!-- Icon -->
    <script src="https://kit.fontawesome.com/a77fc736a8.js" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body>
    <div class="main-container">
        <div class="container-1">
            <a href="../mainpage/index.php" class="back-arrow"> <i class="fa-solid fa-angle-left"></i><span> Back</span></a>
            <img src="../../assets/binotify-icon.png" alt="">
            <h1 class="binotify-title"> Binotify </h1>
            <p>Hear Your Music</p>
        </div>
        <div class="container-2">
            <div class="navigation">
                <a href="register.php"><span>Register</span></a>
            </div>
            <div class="login-form">
                <h3>Login</h3>
                <p>Welcome! Fill username and password to continue!</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="username">
                        <label for="username">Username</label>
                        <br>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <br>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>

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
    const errorContainer = document.querySelector(".error-container");
    const btnClose = document.querySelector("#close");

    btnClose.addEventListener('click', () =>{
        errorContainer.classList.remove('show');
    })
</script>
</body>
</html>
