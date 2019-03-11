<?php
//MySQLサーバ接続
require_once('function/db_conect.php');

// セレクトボックスの連想配列
$select_options = ['black'=>'黒','red'=>'赤','blue'=>'青','yellow'=>'黄','green'=>'緑'];
    
// POSTでアクセスされたら投稿処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
        
    // バリデーションを行う
    if (strlen($_POST['name']) === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($_POST['name'],'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    }
    
    if (strlen($_POST['comment']) === 0) {
        $errors[] = "本文は入力必須です。";
    } else if (mb_strlen($_POST['comment'],'UTF-8') > 100) {
        $errors[] = "本文は１００文字以内です。";
    }
    
    if (!array_key_exists($_POST['color'], $select_options)) {
       $errors[] = "文字色が不正です"; 
    }
    
    if (strlen($_POST['password']) !== 0) {
        if (strlen($_POST['password']) < 4) {
            $errors[] = " パスワードは4文字以上です。";
        }
    
        if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
            $errors[] = " パスワードは半角英数字です。";
        }
    }
    // 画像サイズチェック
    if ($_FILES['picture']['error'] === 2) {
        $errors[] = "サイズが１Mを超えています。";
    }
    
    // 画像ファイルの不正チェック
    $posted_user_img = $_FILES['picture']['tmp_name'];
    // MIMEタイプの取得
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $true_picture_type = $finfo->file($posted_user_img);
    // 配列に投稿を許可する拡張子を入れる
    $picture_types = [
        'image/png',
        'image/gif',
        'image/jpeg'
    ];
    
    // ファイルサイズが大きい時はここではエラーを出さない
    if ((!in_array($true_picture_type, $picture_types)) && (strlen($true_picture_type) !==0)) {
        $errors[] = "画像が不正です。";
    }
    
    // 成功した場合はDBへ保存してsend.phpにリダイレクトする
    if (empty($errors)) {
        // エラーがない時の画像処理
        $file = 'images/' . basename($_FILES['picture']['name']);
        // ファイルを一時フォルダから指定したディレクトリに移動
        move_uploaded_file($_FILES['picture']['tmp_name'], $file);
        
        //パスワードが入力されない時の処理
        if (empty($_POST['password'])) {
            $password = null;
        } else {
            $password = $_POST['password'];
        }
        // 画像が投稿されない時の処理
        if (empty($_FILES['picture']['name'])) {
            $picture = null;
        } else {
            $picture = $_FILES['picture']['name'];
        }
        
        $sql = 'INSERT INTO post (name,comment,color,password,picture) VALUES(:name,:comment,:color,:password,:picture)';
        
        $statement = db_conect()->prepare($sql);
        
        $statement->bindParam(':name', $_POST['name']);
        $statement->bindParam(':comment', $_POST['comment']);
        $statement->bindParam(':color', $_POST['color']);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':picture', $picture);
        
        
        $statement->execute();
        
        $statement = null;

        header('Location: send.php');
        exit;
    }
// GETでアクセスされたら一覧表示用にDBから投稿を取得する
} else {
    $sql = 'SELECT * FROM post ORDER BY created_at DESC';
    
    $statement = db_conect()->prepare($sql);
    
    $statement->execute();
    
    $records = $statement->fetchAll();
}
    
    
$statement = null;

$database = null;
 
require_once('function/function.php');
include('views/index.php');