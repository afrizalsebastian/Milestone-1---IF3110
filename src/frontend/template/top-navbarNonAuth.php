<?php

function topNavbarNonAuth(){
    if (isset($_SESSION["search"])){
        $value = $_SESSION["search"];
    }else{
        $value = "";
    }
    $html = <<< "EOT"
    <nav class="top-navbar">
        <div class="search-box">
            <form action="" method="POST">
                <input type="text" name="search" placeholder="Search" value={$value}>
                <button type="submit" name="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="auth">
            <ul>
                <li class="register-btn"><a href="../auth/register.php">Register</a></li>
                <li class="login-btn"><a href="../auth/login.php">Login</a></li>
            </ul>
        </div>
    </nav>
EOT;
    echo $html;
}