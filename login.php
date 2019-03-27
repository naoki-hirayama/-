<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once('function/db_connect.php');
require_once('function/function.php');
require_once('function/UserRepository.php');
$database = db_connect();
$user_repository = new UserRepository($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_result = $user_repository->login($_POST['login_id'], $_POST['password']);
    
    if (is_numeric($login_result)) {
        $_SESSION['user_id'] = $login_result;
        header('Location: index.php');
        exit;
    } else {
        $errors[] = $login_result;
    }
} 

include('views/login.php');