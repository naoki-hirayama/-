<?php
session_start();

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if (isset($_POST['login'])) {
    
    $sql = 'SELECT * FROM users WHERE login_id = :login_id';
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':login_id', $_POST['login_id']);
    
    $statement->execute();
    $users_table = $statement->fetch();
    
    $user_id = $users_table['user_id'];
    $username = $users_table['username'];
    $login_id = $users_table['login_id'];
    $hashed_password  = $users_table['password'];
    
    $statement = null;
    
    // バリデーション
    $errors = [];
    
    if (!password_verify($_POST['password'], $hashed_password)) {
        $errors[] = "パスワードまたはログインidが間違っています。";    
    } 
    
    if (empty($errors)) {
       
        $_SESSION['login_id'] = $login_id;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    }
}
    

include('views/login.php');