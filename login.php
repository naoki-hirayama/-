<?php
session_start();

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $sql = 'SELECT * FROM users WHERE login_id = :login_id';
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':login_id', $_POST['login_id']);
    
    $statement->execute();
    $user = $statement->fetch();
    
    $user_id = $user['id'];
    $name = $user['name'];
    $login_id = $user['login_id'];
    $hashed_password  = $user['password'];
    
    $statement = null;
    
    // バリデーション
    $errors = [];
    
    if (!password_verify($_POST['password'], $hashed_password)) {
        $errors[] = "パスワードまたはログインidが間違っています。";    
    } 
    
    if (empty($errors)) {
       
        $_SESSION['login_id'] = $login_id;
        $_SESSION['name'] = $name;
        header('Location: index.php');
        exit;
    }
}
    

include('views/login.php');