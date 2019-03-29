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
$user_repository = new UserRepository($database);
$user_info = $user_repository->fetchById($_SESSION['user_id']);
$picture_max_size = $user_repository::MAX_PICTURE_SIZE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $_POST['picture'] = $_FILES['picture'];
    $errors =  $user_repository->validate($_POST, $_SESSION['user_id']);
    
    if(empty($errors)) {
        $user_repository->edit($_SESSION['user_id'], $_POST);
        header('Location: profile.php?id='.$user_info['id'].'');
        exit;
    }
}

include('views/edit.php');