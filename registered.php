<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('function/UserRepository.php');
$database = db_connect();
$user_repository = new UserRepository($database);
$user_info = $user_repository->getUserDetailByUserId($_SESSION['user_id']);

include('views/registered.php');