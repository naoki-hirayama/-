<?php

// PDO のインスタンスを生成して、MySQLサーバに接続
function db_connect($dbname = 'bbs',$username = 'root',$password = '') {
    return new PDO("mysql:host=localhost;dbname={$dbname};charset=UTF8;", $username, $password);
}

