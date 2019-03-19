<?php
session_start();

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if (isset($_POST['signup'])) {
    $sql = 'SELECT * FROM users WHERE login_id = :login_id';
    $statement = $database->prepare($sql);
    $statement->bindParam(':login_id', $_POST['login_id']);
    $statement->execute();
    $exist_login_id = $statement->fetch();
    
    $errors = [];
    $username = trim(mb_convert_kana($_POST['username'], 's'));
    if (mb_strlen($username, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($username, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    }
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login_id'])) {
        $errors[] = "ログインIDは半角英数字です。";
    } else if (mb_strlen($_POST['login_id'], 'UTF-8') < 4) {
        $errors[] = "ログインIDは4文字以上です。";
    } else if (mb_strlen($_POST['login_id'], 'UTF-8') > 15) {
        $errors[] = "ログインIDは15文字以内です。";
    }

    if ($exist_login_id !== false) {
        $errors[] = "このログインIDはすでに存在します。";
    }
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
        $errors[] = " パスワードは半角英数字です。";
    } else if (mb_strlen($_POST['password'], 'UTF-8') < 4) {
        $errors[] = "パスワードが短すぎます";
    } else if (mb_strlen($_POST['password'], 'UTF-8') > 30) {
        $errors[] = "パスワードが長すぎます。";
    } else if ($_POST['password'] === null) {
        $errors[] = "パスワードが不正です。";
    }
    
    //入力されたパスワードと確認パスワードが一致したら
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "パスワードが一致しません。";
    } else {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    if (empty($errors)) {
        
        $sql = 'INSERT INTO users (username,login_id,password) VALUES (:username,:login_id,:password)';
            
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':username', $_POST['username']);
        $statement->bindParam(':login_id', $_POST['login_id']);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        $statement = null;
        
        $_SESSION['login_id'] = $_POST['login_id'];
        $_SESSION['username'] = $_POST['username'];
        
        header('Location: registered.php');
        exit;
    }
}
    
include('views/register.php');