<?php
session_start();

if (isset($_SESSION['login_id'], $_SESSION['name'])) {
    session_destroy();

    $header_title = 'ログアウトしました。';
    include('views/layouts/header.php');
    include('views/logout.php');
    include('views/layouts/footer.php');
    
} else {
    header("Location: login.php");
    exit;
}










