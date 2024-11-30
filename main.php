<?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {


        if (!isset($_SESSION['user'])) {
            header('Location: ./login.php');
            exit;
        }

        header('Location: ./main.html');
        exit;
    } else if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            exit;
        }
        
        $action = $_POST['action'] ?? '';
        if($action === "exit") {
            session_unset();
            header('Location: ./login.php');
        }
    }
?>
