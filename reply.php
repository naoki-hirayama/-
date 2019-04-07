<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');
require_once('models/PostRepository.php');
require_once('models/ReplyRepository.php');
$database = db_connect();

$user_repository = new UserRepository($database);
$post_repository = new PostRepository($database);
$reply_repository = new ReplyRepository($database);
$picture_max_size = $reply_repository::MAX_PICTURE_SIZE;
$select_color_options = ReplyRepository::getSelectColorOptions();

$post = $post_repository->fetchById($_GET['id']);
$current_user_name = $user_repository->fetchById($post_repository->fetchById($_GET['id'])['user_id']);

if ($post === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = $_POST;
    if (isset($_FILES['picture'])) {
        $values['picture'] = $_FILES['picture'];
    }
    $errors = $reply_repository->validate($values);
    if (empty($errors)) {
        if (isset($_SESSION['user_id'])) {
            $reply_repository->create($post['id'], $values, $_SESSION['user_id']);
        } else {
            $reply_repository->create($post['id'], $values);
        }
        header('Location: reply.php?id='.$post['id'].'');
        exit;
    }
} else {
    $reply_posts = $reply_repository->fetchByPostId($post['id']);
    $total_replies = $reply_repository->fetchCountByPostId($post['id']);
    
    $user_ids = [];
    foreach ($reply_posts as $reply_post) {
        if (isset($reply_post['user_id'])) {
            $user_ids[] = $reply_post['user_id'];
        }
    }
    
    if (!empty($user_ids)) {
        $users = $user_repository->fetchByIds($user_ids);
        $user_names_are_key_as_user_ids = array_column($users, 'name', 'id');
    }
}    

include('views/reply.php');