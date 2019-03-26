<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if (isset($_SESSION['user_id'])) {
    $user_info = select_users($_SESSION['user_id']);
}
$user = select_users($_GET['id']);

if ($user === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

include('views/profile.php');