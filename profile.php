<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if (isset($_SESSION['user_id'])) {
    $user_info = fetch_user_by_id($_SESSION['user_id'], $database);
}

$user = fetch_user_by_id($_GET['id'], $database);

if ($user === false) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

include('views/profile.php');