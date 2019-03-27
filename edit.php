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

$picture_max_size = 1*1024*1024; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $validate_result = $user_repository->validateEdit($_POST['name'], $_POST['login_id'], $_FILES['picture']['name'], $_POST['comment'], $_SESSION['user_id']);
    
    if (empty($validate_result)) {
        if (!empty($_FILES['picture']['tmp_name'])) {
            // エラーがなくて画像が投稿された時の画像処理 
            $specific_num = uniqid(mt_rand()); 
            $rename_file = $specific_num.'.'.basename($picture_type);
            $rename_file_path = 'userimages/'.$rename_file;
            move_uploaded_file($_FILES['picture']['tmp_name'], $rename_file_path);
        }
        // 画像が投稿されない時の処理
        if (strlen($_FILES['picture']['name']) === 0 && empty($user_info['picture'])) {
            $picture = null;
        } else if (strlen($_FILES['picture']['name']) !== 0 && empty($user_info['picture'])) {
            $picture = $rename_file;
        } else if (strlen($_FILES['picture']['name']) !== 0 && !empty($user_info['picture'])) {
            $picture = $rename_file;
            unlink("userimages/{$user_info['picture']}");
        } else {
            $picture = $user_info['picture'];
        }
        //一言コメントが入力されない時の処理
        if (strlen($_POST['comment']) === 0) {
            $comment = null;
        } else {
            $comment = $_POST['comment'];
        }
        
        $user_repository->edit($_SESSION['user_id'], $_POST['name'], $_POST['login_id'], $picture, $comment);
        
        header('Location: profile.php?id='.$user_info['id'].'');
        exit;
    }
}

include('views/edit.php');