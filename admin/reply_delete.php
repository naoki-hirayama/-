<?php
require_once('../function/db_connect.php');
require_once('../function/function.php');
require_once('../models/ReplyRepository.php');
$database = db_connect();

$reply_repository = new ReplyRepository($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_repository->delete($_POST['reply_id']);
    header('Location: postdetail.php?id='.$_POST['post_id'].'');
    exit;
}