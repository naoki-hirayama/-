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
    //deleted.phpにポスト送信jqueryで
    $post_repository->delete($_POST['id']);
    $reply_repository->deleteByPostId($_POST['id']);
    // header('Location: deleted.php?id='.$_POST['id'].'');
    // exit;
}


include('../admin/views/deleted.php');