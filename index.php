<?php

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        if (isset($_SESSION['user']))
            echo file_get_contents("./main.html");
        else
            echo file_get_contents("./login.html");
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $db = new mysqli("localhost", "root", "", "Maria");
        if (isset($_POST['isLogin'])) {
            
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            $query = "SELECT * FROM Users WHERE username = '$login'";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
        
                if ($user['pass'] === $password) {
                    $_SESSION['user'] = $user['username']; 
                    echo "succes";
                    exit;
                }
            }

            echo "error";
            exit;
        } else if(isset($_POST["isExit"])) {
            session_unset();
            session_destroy();
            echo "succes";
            exit;
        } else if(isset($_POST["isGetImgs"]) && isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
            $query = "SELECT * FROM Users WHERE username = '$username'";
            $user = $db->query($query)->fetch_assoc();
            $user_id = $user["id"];
            $query = "SELECT file_name FROM Images WHERE user_id = $user_id";
            echo json_encode($db->query($query)->fetch_all()); // <-- Тут были изменения
            exit;
        }
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && isset($_SESSION['user'])) {
            $uploadDir = 'uploads/'; // Папка для сохранения файлов
            
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = uniqid() . basename($_FILES['image']['name']);
            $uploadFilePath = $uploadDir .  $fileName;

            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                echo 'succes';
                
                $username = $_SESSION['user'];
                $query = "SELECT * FROM Users WHERE username = '$username'";
                $user = $db->query($query)->fetch_assoc();
                $user_id = $user["id"];

                $query = "INSERT INTO Images (user_id, file_name) VALUES ($user_id, '$fileName');";
                $db->query($query);
            } else {
                echo "error";
            }
        }
    }
$db = new mysqli("localhost", "root", "", "Maria");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
