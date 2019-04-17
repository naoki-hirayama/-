<?php
require_once('../function/db_connect.php');
require_once('../function/Pager.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');
$database = db_connect();
$user_repository = new UserRepository($database);
$post_repository = new PostRepository($database);
$reply_repository = new ReplyRepository($database);
$picture_max_size = $reply_repository::MAX_PICTURE_SIZE;
$select_color_options = ReplyRepository::getSelectColorOptions();
$current_user_name = $user_repository->fetchById($post_repository->fetchById($_GET['id'])['user_id']);

$post = $post_repository->fetchById($_GET['id']);
$reply_posts = $reply_repository->fetchByPostId($post['id']);
if ($post === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

$reply_posts = $reply_repository->fetchByPostId($post['id']);

$user_ids = [];
foreach ($reply_posts as $reply_post) {
    if (isset($reply_post['user_id'])) {
        $user_ids[] = $reply_post['user_id'];
    }
}

if (!empty($user_ids)) {
    $users = $user_repository->fetchByIds($user_ids);
    $user_names = array_column($users, 'name', 'id');
}

include('../admin/views/postdetail.php');