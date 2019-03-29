<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once('function/db_connect.php');
require_once('function/function.php');
require_once('models/UserRepository.php');
$database = db_connect();
$user_repository = new UserRepository($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $user_repository->validate($_POST);
    
    if (empty($errors)) {
        
        $user_id = $user_repository->register($_POST);

        $_SESSION['user_id'] = $user_id;
        
        header('Location: registered.php');
        exit;
    }
}

include('views/register.php');