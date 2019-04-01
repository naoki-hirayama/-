<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');

$database = db_connect();
$table_name = 'users';
$user_repository = new UserRepository($database, $table_name);
if (isset($_SESSION['user_id'])) {
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}

$header_title = '削除しました。';

include('views/deleted.php');



