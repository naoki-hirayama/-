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

$picture_max_size = $user_repository::MAX_PICTURE_SIZE;
$select_color_options = PostRepository::getSelectColorOptions();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = $_POST;

    $errors = $post_repository->validate($_POST);
    
    if (empty($errors)) {
        $post_repository->edit($_POST);
    } else {
        echo json_encode($errors);
    }
}
