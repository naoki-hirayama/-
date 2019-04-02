<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');
$database = db_connect();
$user_repository = new UserRepository($database);
if (isset($_SESSION['user_id'])) {
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}

$user = $user_repository->fetchById($_GET['id']);

if ($user === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

include('views/profile.php');