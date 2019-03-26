<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();
$user_info = fetch_user_by_id($_SESSION['user_id'], $database);

include('views/registered.php');