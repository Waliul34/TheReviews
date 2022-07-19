<?php

$dBserver = "localhost";
$dBusername = "root";
$dBpassword = "";
$dBdatabase = "thereviews";

$conn = mysqli_connect($dBserver, $dBusername, $dBpassword, $dBdatabase);

if(!$conn)
{
    die("<script>alert('Connection failed!');</script>");
}


?>