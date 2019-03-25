<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //if文で書く修正 
    $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':login_id', $_POST['login_id']);
    
    $statement->execute();
    $user = $statement->fetch();
    
    $statement = null;  
    
    $errors = [];
    if ($user === false) {
        $errors[] = "パスワードまたはログインidが間違っています。";
    } else if (!password_verify($_POST['password'], $user['password'])) {
        $errors[] = "パスワードまたはログインidが間違っています。";
    }
    
    if (empty($errors)) {
        $_SESSION['username'] = $user['name'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_id'] = $user['login_id'];
        if (isset($user['picture'])) {
            $_SESSION['picture'] = $user['picture'];
        }
        header('Location: index.php');
        exit;
    }
} 

include('views/login.php');