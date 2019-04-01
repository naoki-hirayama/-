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
$table_name = 'users';
$user_repository = new UserRepository($database, $table_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $user_repository->fetchByLoginIdAndPassword($_POST['login_id'], $_POST['password']);
    if ($user === false) {
        $errors[] = 'ログインIDまたはパスワードに誤りがあります';
    } else {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    }
} 

include('views/login.php');