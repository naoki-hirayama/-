<?php
require_once('../function/db_connect.php');
require_once('../function/Pager.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');

$database = db_connect();
$post_repository = new PostRepository($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = $_POST;
    $errors = $post_repository->validate($values);
    
    if (empty($errors)) {
        $post_repository->edit($values['id'], $values);
        $response = [];
        $response['status'] = true;
        $response['post'] = $post_repository->fetchById($values['id']);
        echo json_encode($response);
    } else {
        $response = [];
        $response['status'] = false;
        $response['errors'] = $errors;
        echo json_encode($response);
    }
}