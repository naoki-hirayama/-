<?php
    // MySQLサーバ接続に必要な値を変数に代入
    $username = 'root';
    $password = '';
    
    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=bbs;charset=UTF8;', $username, $password);
    
