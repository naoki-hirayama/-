<?php
session_start();
require_once('function/db_connect.php');
require_once('function/function.php');

$user_info = select_users($_SESSION['user_id']);
$header_title = '投稿成功';
include('views/send.php');


