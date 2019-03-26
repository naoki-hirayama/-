<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php'); 
$database = db_connect();
if (isset($_SESSION['user_id'])) {
    $user_info = fetch_user_by_id($_SESSION['user_id'], $database);
}

$header_title = '削除しました。';

include('views/deleted.php');



