<?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {


        if (!isset($_SESSION['user'])) {
            header('Location: ./login.php');
            exit;
        }

        header('Location: ./main.html');
        exit;
    }
?>
