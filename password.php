<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
} 
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');

$database = db_connect();
$table_name = 'users';
$user_repository = new UserRepository($database, $table_name);
$user_info = $user_repository->fetchById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $validate_result = $user_repository->validateChangePassword($_SESSION['user_id'], $_POST);
    
    if (empty($validate_result)) {
        
        $user_repository->changePassword($_SESSION['user_id'], $_POST);
        
        header('Location: edit.php');
        exit;
    } else {
        $errors = $validate_result;
    }
}

include('views/password.php');