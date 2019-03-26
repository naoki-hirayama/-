<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    }
} 

include('views/login.php');