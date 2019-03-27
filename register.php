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
    $validate_result = $user_repository->validateRegister($_POST['name'], $_POST['login_id'], $_POST['password'], $_POST['confirm_password']);
    if (empty($validate_result)) {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_id = $user_repository->register($_POST['name'], $_POST['login_id'], $password_hash);
        
        $_SESSION['user_id'] = $user_id;
        
        header('Location: registered.php');
        exit;
    } else {
        $errors = $validate_result;
        
    }
}

include('views/register.php');