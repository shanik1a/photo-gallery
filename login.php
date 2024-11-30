<?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_SESSION['user'])) {
            header('Location: ./main.php');
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $db = new mysqli("localhost", "root", "", "Maria");
        
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $query = "SELECT * FROM Users WHERE username = '$login'";
        $result = $db->query($query);


        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            // Проверяем пароль
            if ($user['pass'] === $password) { // Если пароли не хэшированы
                $_SESSION['user'] = $user['username']; // Создаём сессию
                header('Location: ./main.php');
                exit;
            }
        }
    }
?>