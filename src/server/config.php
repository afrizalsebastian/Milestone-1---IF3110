<?php
$dbname = 'binotify';
$hostname = 'localhost';
$username = 'root';
$password = '';
//admin1 = adminpass
// Connect on DB

//Jika docker 
//hostname = 'database'
//password = 'rootpass'
try {
    $pdo = new PDO("mysql:dbname=$dbname;host=$hostname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}