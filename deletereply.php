<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');
require_once('models/PostRepository.php');
require_once('models/ReplyRepository.php');

$database = db_connect();
$post_repository = new PostRepository($database);
$reply_repository = new ReplyRepository($database);

if (isset($_SESSION['user_id'])) {
    $user_repository = new UserRepository($database);
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}

$reply_post = $reply_repository->fetchById($_GET['id']);

if ($reply_post === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
} else if (!isset($reply_post['password']) && !isset($reply_post['user_id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else if (isset($reply_post['user_id']) && $reply_post['user_id'] !== $_SESSION['user_id']) {
    header('HTTP/1.1 400 Bad Request');
    exit;
} else {
    if (isset($reply_post['password'])) {
        $origin_password = $reply_post['password'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (empty($reply_post['user_id'])) { 
        if ($origin_password !== $_POST['password_input']) {
            $errors[] = "パスワードが違います";
        }
    }
    
    if (empty($errors)) {
        $reply_repository->delete($reply_post['id']);
        
        header('Location: deletedreply.php?id='.$reply_post['post_id'].'');
        exit;
    }
}

include('views/deletereply.php');