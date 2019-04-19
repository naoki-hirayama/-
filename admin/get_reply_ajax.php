<?php 
require_once('../function/db_connect.php');
require_once('../function/Pager.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');

$database = db_connect();
$reply_repository = new ReplyRepository($database);

$reply_post_id = $_GET['reply_id'];

$reply_post = $reply_repository->fetchById($reply_post_id);
echo json_encode($reply_post);
