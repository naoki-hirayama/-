<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');
require_once('models/PostRepository.php');

$database = db_connect();
$post_repository = new PostRepository($database);

if (isset($_SESSION['user_id'])) {
    $user_repository = new UserRepository($database);
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}
$post = $post_repository->fetchById($_GET['id']);

if ($post === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
} else if (!isset($post['delete_password']) && !isset($post['user_id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else if (isset($post['user_id']) && $post['user_id'] !== $_SESSION['user_id']) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else {
    if (isset($post['delete_password'])) {
        $origin_password = $post['delete_password'];
    }
}

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
        $post_repository->delete($_GET['id']);
        header('Location: deleted.php');
        exit;
    }
}

include('views/delete.php');