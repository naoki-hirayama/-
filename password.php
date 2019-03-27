<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
} 
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('function/UserRepository.php');

$database = db_connect();
$user_repository = new UserRepository($database);
$user_info = $user_repository->getUserDetailByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $validate_result = $user_repository->validatePassword($_SESSION['user_id'], $_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);
    
    if (!is_array($validate_result)) {
        
        $user_repository->editPassword($_SESSION['user_id'], $validate_result);
        
        header('Location: edit.php');
        exit;
    } else {
        $errors = $validate_result;
    }
}

include('views/password.php');