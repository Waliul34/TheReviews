<?php

    session_start();
    session_destroy();
    header("Location: index.php");
    setcookie("mode", "", -1);

?>