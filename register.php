<?php
session_start();

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $name = trim(mb_convert_kana($_POST['name'], 's'));
    if (mb_strlen($name, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($name, 'UTF-8') < 4) {
        $errors[] = "名前は4文字以上です。";
    } else if (mb_strlen($name, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    }
    
    $login_id = trim(mb_convert_kana($_POST['login_id'], 's'));
    if (mb_strlen($login_id, 'UTF-8') === 0) {
        $errors[] = "ログインIDは入力必須です。";
    } else if (!preg_match("/^[a-zA-Z0-9]+$/", $login_id)) {
        $errors[] = "ログインIDは半角英数字です。";
    } else if (mb_strlen($login_id, 'UTF-8') < 4) {
        $errors[] = "ログインIDは4文字以上です。";
    } else if (mb_strlen($login_id, 'UTF-8') > 15) {
        $errors[] = "ログインIDは15文字以内です。";
    } else {
        $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
        $statement = $database->prepare($sql);
        $statement->bindParam(':login_id', $login_id);
        $statement->execute();
        $tmp_user = $statement->fetch();
        $errors = [];
        
        if ($tmp_user !== false) { 
            $errors[] = "このログインIDはすでに存在します。";
        }
    }
    
    $password = trim(mb_convert_kana($_POST['password'], 's'));
    if (mb_strlen($password, 'UTF-8') === 0) {
        $errors[] = "パスワードは入力必須です。";
    } else if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $errors[] = "パスワードは半角英数字です。";
    } else if (mb_strlen($password, 'UTF-8') < 4) {
        $errors[] = "パスワードは４文字以上です。";
    } else if (mb_strlen($password, 'UTF-8') > 30) {
        $errors[] = "パスワードが長すぎます。";
    }
    
    //入力されたパスワードと確認パスワードが一致したら
    if ($password !== $_POST['confirm_password']) {
        $errors[] = "パスワードが一致しません。";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
    
    if (empty($errors)) {
        
        $sql = 'INSERT INTO users (name,login_id,password) VALUES (:name,:login_id,:password)';
            
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        $statement = null;
        
        $_SESSION['username'] = $name;
        
        
        header('Location: registered.php');
        exit;
    }
}

include('views/register.php');