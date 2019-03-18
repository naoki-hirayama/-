<?php
//MySQLサーバ接続
require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();

if (isset($_POST['signup'])) {
    $errors = [];
    // バリデーション
    $username = trim(mb_convert_kana($_POST['username'], 's'));
    if (mb_strlen($username, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($username, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    } 
    
    
    
}
    
include('views/register.php');