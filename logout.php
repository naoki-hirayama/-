<?php

session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    
    unset($_SESSION['login_id']);
    unset($_SESSION['username']);
  
    $header_title = 'ログアウトしました。';
    include('views/layouts/header.php');
    include('views/logout.php');
    include('views/layouts/footer.php');
    
} else {
    header("Location: login.php");
}










