<?php

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['registration'])) {
            echo file_get_contents("./registration.html");
        } else if (isset($_SESSION['user_id'])) {
            echo file_get_contents("./main.html");
        } else {
            echo file_get_contents("./login.html");
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new mysqli("localhost", "root", "", "Maria");

        if (isset($_POST['isLogin'])) {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            $query = "SELECT * FROM Users WHERE username = '$login'";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['pass'])) {
                    $_SESSION['user_id'] = $user['id']; 
                    echo "success";
                    exit;
                }
            }

            echo "error";
            exit;
        } else if (isset($_POST['isRegister'])) {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';

            $query = "SELECT * FROM Users WHERE username = '$login'";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                echo "error: user_exists";
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO Users (username, pass) VALUES ('$login', '$hashed_password')";

            if ($db->query($query)) {
                $user_id = $db->insert_id; // Получаем ID только что созданного пользователя
                $_SESSION['user_id'] = $user_id; // Сохраняем ID в сессии
                echo "success";
                exit;
            }

            echo "error";
            exit;
        } else if (isset($_POST['isExit'])) {
            session_unset();
            session_destroy();
            echo "success";
            exit;
        } else if (isset($_POST['isGetImgs']) && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $query = "SELECT file_name, caption FROM Images WHERE user_id = $user_id";
            echo json_encode($db->query($query)->fetch_all());
            exit;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && isset($_SESSION['user_id'])) {
            $uploadDir = 'uploads/';

            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = uniqid() . basename($_FILES['image']['name']);
            $uploadFilePath = $uploadDir . $fileName;

            $caption = $_POST['caption'] ?? null;
            if ($caption === null) {
                echo "error: caption is required";
                exit;
            }

            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                echo 'success';

                $user_id = $_SESSION['user_id'];
                $query = "INSERT INTO Images (user_id, file_name, caption) VALUES ($user_id, '$fileName', '$caption')";
                $db->query($query);
            } else {
                echo "error";
            }
        }
    }

?>