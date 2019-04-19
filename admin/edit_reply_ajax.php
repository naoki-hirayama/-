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

    $errors = $post_repository->validate($values);
    
    if (empty($errors)) {
        $reply_repository->edit($values, $values['id']);
        $response = [];
        $response['status'] = true;
        $response['reply'] = $reply_repository->fetchById($values['id']);
        echo json_encode($response);
    } else {
        $response = [];
        $response['status'] = false;
        $response['errors'] = $errors;
        echo json_encode($response);
    }
}