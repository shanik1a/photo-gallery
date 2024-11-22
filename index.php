<?php

    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ./login.html');
        exit;
    }

    header('Location: ./main.php');
    exit;

?>
