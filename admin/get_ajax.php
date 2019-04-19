<?php 
require_once('../function/db_connect.php');
require_once('../function/Pager.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');

$database = db_connect();
$post_repository = new PostRepository($database);

$post_id = $_GET['id'];

$post = $post_repository->fetchById($post_id);
echo json_encode($post);




