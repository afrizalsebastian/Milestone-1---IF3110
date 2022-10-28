<?php

function sideNavbarUser(){
    $html = <<< "EOT"
    <nav class="side-navbar">
        <ul>
            <li>
                <a href="../mainpage/index.php" class="logo">
                    <img src="../../assets/binotify-icon.png" alt="">
                    <span class = "nav-item">Binotify</span>
                </a>
            </li>
            <li>
                <a href="../mainpage/index.php">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-item">Home</span>
                </a>
            </li>
            <li>
                <a href="search.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="nav-item">Search</span>
                </a>
            </li>
            <li>
                <a href="daftar-album.php">
                <i class="fa-solid fa-layer-group"></i>
                    <span class="nav-item">All Album</span>
                </a>
            </li>
        </ul>
    </nav>
EOT;

    echo $html;
}