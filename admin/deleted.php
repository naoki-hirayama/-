<?php
require_once('../function/db_connect.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');
$database = db_connect();

$post_repository = new PostRepository($database);
$reply_repository = new ReplyRepository($database);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $post_repository->delete($_POST['post_id']);
    $reply_repository->deleteByPostId($_POST['post_id']);
}

include('../admin/views/deleted.php');