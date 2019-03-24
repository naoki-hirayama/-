<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php'); 

$database = db_connect();

$sql = 'SELECT * FROM post WHERE id = :id';

$statement = $database->prepare($sql);

$statement->bindParam(':id', $_GET['id']);

$statement->execute();

$post = $statement->fetch(PDO::FETCH_ASSOC);

if ($post === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
} else if (empty($post['password']) && empty($post['user_id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else if (($post['user_id'] !== $_SESSION['user_id']) && (!empty($post['user_id']))) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else {
    $origin_password = $post['password'];
}
// delete.phpからPOST送信された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //パスワードが一致しない、不正時のエラー処理　user_idが存在していたら
    $errors = [];
    
    if (empty($post['user_id'])) { 
        if ($origin_password !== $_POST['password_input']) {
            $errors[] = "パスワードが違います";
        }
    }
    //パスワードが一致した時 
    if (empty($errors)) {
        
        $sql = 'DELETE FROM post WHERE id = :id';
        
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':id', $_GET['id']);
        
        $statement->execute();
        
        $statement = null;
        // 投稿に画像がある時
        if (($post['picture']) !== null) {
            unlink("images/{$post['picture']}");
        }
        
        header('Location: deleted.php');
        exit;
    }
}

$statement = null;

include('views/delete.php');