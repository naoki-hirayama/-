<?php
//MySQLサーバ接続
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();



if (isset($_POST['signup'])) {
    $errors = [];
    // バリデーション　　パスワードにnullが入らないようにする
    
    $username = trim(mb_convert_kana($_POST['username'], 's'));
    if (mb_strlen($username, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($username, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    }
    
    if (mb_strlen($_POST['login_id'], 'UTF-8') < 4) {
        $errors[] = "ログインIDは4文字以上です。";
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login_id'])) {
        $errors[] = "ログインIDは半角英数字です。";
    }
    
    if (mb_strlen($_POST['password'], 'UTF-8') < 4) {
        $errors[] = "パスワードは4文字以上です";
    } else if (mb_strlen($_POST['password'], 'UTF-8') > 30) {
        $errors[] = "パスワードが不正です。";
    } else if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
            $errors[] = " パスワードは半角英数字です。";
    }
    
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "パスワードが一致しません。";
    } else {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    // var_dump($password_hash);exit;
    if (empty($errors)) {
        // エラーがなっかたら登録　try catchもしユーザーidが被ってたらエラーを返す　トランザクション
        // インサート文
        $sql = 'INSERT INTO users (username,login_id,password) VALUES (:username,:login_id,:password)';
            
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':username', $_POST['username']);
        $statement->bindParam(':login_id', $_POST['login_id']);
        $statement->bindParam(':password', $password_hash);
        
        $statement->execute();
        
        $statement = null;
    
        header('Location: index.php');
        exit;    
    }
    
    
    
    
}
    
include('views/register.php');