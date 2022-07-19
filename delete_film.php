<?php

include_once('account_session.php');
if($isAdmin)
{
    $f_id =  $_GET['f_id'];

    $qu = "SELECT * FROM films WHERE f_id = $f_id;";
    $re = mysqli_query($conn, $qu);
    $da = mysqli_fetch_assoc($re);
    $img = $da['poster_name'];

    $path = "img/" . $img;
    unlink($path);
    $query = "DELETE FROM films WHERE f_id = $f_id;";
    $res_q = mysqli_query($conn, $query);
    if($res_q)
    {
        echo "<script>alert('Film successfully deleted.');document.location.href='films.php'</script>";
    }
    else
    {
        echo "<script>alert('Something went wrong.')</script>";
    }
}

?>