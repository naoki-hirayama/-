<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('function/UserRepository.php');

$database = db_connect();
if (isset($_SESSION['user_id'])) {
    $user_repository = new UserRepository($database);
    $user_info = $user_repository->getUserDetailByUserId($_SESSION['user_id']);
}

$header_title = '削除しました。';

include('views/deleted.php');



