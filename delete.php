<?php
//MySQLサーバ接続
require_once('function/db_connect.php');
require_once('function/function.php'); 

$database = db_connect();

$sql = 'SELECT * FROM post WHERE id = :id';

$statement = $database->prepare($sql);

$statement->bindParam(':id', $_GET['id']);

$statement->execute();

$record = $statement->fetch(PDO::FETCH_ASSOC);;

if ($record === false) {
    header('HTTP/1.1 404 Not Found') ;
    exit;
} elseif (empty($record['password'])) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else {
    $origin_password = $record['password'];
}
// delete.phpからPOST送信された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //パスワードが一致しない、不正時のエラー処理
    $errors = [];
    if ($origin_password !== $_POST['password_input']) {
        $errors[] = "パスワードが違います";
    }
    //パスワードが一致した時 
    if (empty($errors)) {
        
        $sql = 'DELETE FROM post WHERE id = :id';
        
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':id', $_GET['id']);
        
        $statement->execute();
        
        $statement = null;
        // 投稿に画像がある時
        if (($record['picture']) !== null) {
            unlink("images/{$record['picture']}");
        }
        
        header('Location: deleted.php');
        exit;
    }
}

$statement = null;

include('views/delete.php');