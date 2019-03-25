<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

require_once('function/db_connect.php');
require_once('function/function.php');
$database = db_connect();
$picture_max_size = 1*1024*1024; 

$sql = 'SELECT * FROM users WHERE id = :id';

$statement = $database->prepare($sql);

$statement->bindParam(':id', $_SESSION['user_id']);

$statement->execute();

$user = $statement->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $name = trim(mb_convert_kana($_POST['name'], 's'));
    if (mb_strlen($name, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($name, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    }
    $login_id = trim(mb_convert_kana($_POST['login_id'], 's'));
    if ($user['login_id'] !== $login_id) {    
        if (mb_strlen($login_id, 'UTF-8') === 0) {
            $errors[] = "ログインIDは入力必須です。";
        } else if (!preg_match("/^[a-zA-Z0-9]+$/", $login_id)) {
            $errors[] = "ログインIDは半角英数字です。";
        } else if (mb_strlen($login_id, 'UTF-8') < 4) {
            $errors[] = "ログインIDは4文字以上です。";
        } else if (mb_strlen($login_id, 'UTF-8') > 15) {
            $errors[] = "ログインIDは15文字以内です。";
        } else {
            $sql = 'SELECT * FROM users WHERE login_id = BINARY :login_id';
            $statement = $database->prepare($sql);
            $statement->bindParam(':login_id', $login_id);
            $statement->execute();
            $tmp_user = $statement->fetch();
            $errors = [];
            
            if ($tmp_user !== false) { 
                $errors[] = "このログインIDはすでに存在します。";
            }
        }
    }
    if (strlen($_FILES['picture']['name']) !== 0) {
        if ($_FILES['picture']['error'] === 2) {
            $errors[] = "サイズが".number_format($picture_max_size)."Bを超えています。";
        } else if ($_FILES['picture']['size'] > $picture_max_size) {
            $errors[] = "不正な操作です。";
        } else {
            // 画像ファイルのMIMEタイプチェック
            $posted_picture = $_FILES['picture']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $picture_type = $finfo->file($posted_picture);
            
            $vaild_picture_types = [
                'image/png',
                'image/gif',
                'image/jpeg'
            ];
            
            if (!in_array($picture_type, $vaild_picture_types)) {
                $errors[] = "画像が不正です。";
            }
        } 
    }
    
    $comment = $_POST['comment'];
    if (strlen($comment) !== 0) {
        $_comment = trim(mb_convert_kana($comment, 's'));
        if (mb_strlen($_comment, 'UTF-8') === 0) {
            $errors[] = "本文を正しく入力してください。";
        } else if (mb_strlen($_comment, 'UTF-8') > 50) {
            $errors[] = "本文は50文字以内です。";
        } 
    }
    // 成功した場合はDBへ保存してprofile.phpにリダイレクトする
    if (empty($errors)) {
        if (!empty($_FILES['picture']['tmp_name'])) {
            // エラーがなくて画像が投稿された時の画像処理 
            $specific_num = uniqid(mt_rand()); 
            $rename_file = $specific_num.'.'.basename($picture_type);
            $rename_file_path = 'userimages/'.$rename_file;
            move_uploaded_file($_FILES['picture']['tmp_name'], $rename_file_path);
        }
        // 画像が投稿されない時の処理
        if (strlen($_FILES['picture']['name']) === 0 && empty($user['picture'])) {
            $picture = null;
        } else if (strlen($_FILES['picture']['name']) !== 0 && empty($user['picture'])) {
            $picture = $rename_file;
        } else if (strlen($_FILES['picture']['name']) !== 0 && !empty($user['picture'])) {
            $picture = $rename_file;
            unlink("userimages/{$user['picture']}");
        } else {
            $picture = $user['picture'];
        }
        //一言コメントが入力されない時の処理
        if (strlen($_comment) === 0) {
            $comment = null;
        } else {
            $comment = $_comment;
        }
        
        $sql = 'UPDATE users SET name = :name, login_id = :login_id, picture = :picture, comment = :comment WHERE id = :id';
        
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':id', $_SESSION['user_id']);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':login_id', $login_id);
        $statement->bindParam(':picture', $picture);
        $statement->bindParam(':comment', $comment);
        
        $statement->execute();
        
        unset($_SESSION['username']);
        $_SESSION['username'] = $name;
        
        if ($picture !== null) {
            $_SESSION['picture'] = $picture;
        }
        
        $statement = null;
        
        header('Location: profile.php?id='.$user['id'].'');
        exit;
    }    
}

include('views/edit.php');