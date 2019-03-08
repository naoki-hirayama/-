<?php

// PDO のインスタンスを生成して、MySQLサーバに接続
function db_conect($dbname = 'bbs',$username = 'root',$password = '') {
    return $db = new PDO("mysql:host=localhost;dbname={$dbname};charset=UTF8;", $username, $password);
}

$database = db_conect();