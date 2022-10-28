<?php
function topNavbarAuth($username){
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
                <li class="nav-username"><i class="fas fa-user"></i><span style="margin-left:2px">$username</span></li>
                <li class="logout-btn"><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
EOT;
    echo $html;
}
?>