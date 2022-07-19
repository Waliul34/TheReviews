<?php

include_once('account_session.php');

$p_id =  $_GET['p_id'];
$query = "SELECT * FROM reviews WHERE p_id = $p_id";
$res_q = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($res_q);
$postUsername = $data['username'];

if($user == $postUsername || $isAdmin)
{
    $query = "DELETE FROM reviews WHERE p_id = $p_id";
    $res_q = mysqli_query($conn, $query);
    if($res_q)
    {
        echo "<script>alert('Review successfully deleted.');document.location.href='my_reviews.php'</script>";
    }
    else
    {
        echo "<script>alert('Something went wrong.')</script>";
    }
}

?>