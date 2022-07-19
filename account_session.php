<?php

include_once('config.php');
session_start();
error_reporting(0);

$now = time();
if($now > $_SESSION['expire'])
    session_destroy();
    
$user = $_SESSION['username'];

if(isset($_SESSION['username']))
    $user = $_SESSION['username'];
else
    $user = "";


$query = "SELECT * FROM admins WHERE username_admin='$user';";
$resultQ = mysqli_query($conn, $query);
$num = mysqli_num_rows($resultQ);
if($num)
    $isAdmin = true;
else
    $isAdmin = false;


?>