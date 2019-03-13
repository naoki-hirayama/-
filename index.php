<?php
//MySQLサーバ接続
require_once('function/db_conect.php');

// セレクトボックスの連想配列
$select_options = ['black'=>'黒','red'=>'赤','blue'=>'青','yellow'=>'黄','green'=>'緑'];
    
// POSTでアクセスされたら投稿処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $pattern="^(\s|　)+$";
    // バリデーション
    if (mb_strlen($_POST['name'], 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($_POST['name'], 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    } else if (mb_ereg_match($pattern,$_POST['name'])) {
        $errors[] = "名前を正しく記入してください。";
    }
    
    if (mb_strlen($_POST['comment'], 'UTF-8') === 0) {
        $errors[] = "本文は入力必須です。";
    } else if (mb_strlen($_POST['comment'], 'UTF-8') > 100) {
        $errors[] = "本文は１００文字以内です。";
    } else if (mb_ereg_match($pattern, $_POST['comment'])) {
        $errors[] = "本文を正しく記入してください";
    }
    
    if (!array_key_exists($_POST['color'], $select_options)) {
       $errors[] = "文字色が不正です"; 
    }
    
    if (strlen($_POST['password']) !== 0) {
        if (mb_strlen($_POST['password'], 'UTF-8') < 4) {
            $errors[] = " パスワードは4文字以上です。";
        }
    
        if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['password'])) {
            $errors[] = " パスワードは半角英数字です。";
        }
    }

    if (strlen($_FILES['picture']['name']) !== 0) {
        if ($_FILES['picture']['error'] === 2) {
            $errors[] = "サイズが１Mを超えています。";
        } else if ($_FILES['picture']['size'] > 1024*1024) {
            $errors[] = "不正な操作です。";
        } else {
            // 画像ファイルのMIMEタイプチェック
            $posted_user_img = $_FILES['picture']['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $true_picture_type = $finfo->file($posted_user_img);
            
            $proper_picture_types = [
                'image/png',
                'image/gif',
                'image/jpeg',
            ];
            
            if (!in_array($true_picture_type, $proper_picture_types)) {
                $errors[] = "画像が不正です。";
            }
        } 
    }
    // 成功した場合はDBへ保存してsend.phpにリダイレクトする
    if (empty($errors)) {
        // エラーがない時の画像処理
        $specific_num = rand();
        $rename_file = $specific_num.$_FILES['picture']['name'];
        $rename_file_path = 'images/'. basename($rename_file);
        // ファイルを一時フォルダから指定したディレクトリに移動
        move_uploaded_file($_FILES['picture']['tmp_name'], $rename_file_path);
        
        //パスワードが入力されない時の処理
        if (strlen($_POST['password']) === 0) {
            $password = null;
        } else {
            $password = $_POST['password'];
        }
        // 画像が投稿されない時の処理
        if (strlen($_FILES['picture']['name']) === 0) {
            $picture = null;
        } else {
            $picture = $rename_file;
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
// GETでアクセスされた時
} else {
    $per_page_records = 3;
    $stmt = db_conect()->query('SELECT COUNT(id) AS CNT FROM post');
    $total_records = $stmt->fetchColumn();
    //合計ページ数を計算
    $total_pages = ceil($total_records / $per_page_records);
    
    if ($_GET['page'] > $total_pages) {
        header('HTTP/1.1 404 Not Found') ;
        exit;
    } else if ($_GET['page'] <= $total_pages) {
        $page = (int)$_GET['page'];
    } else {
        $page = 1;
    }
    
    if ($page > 1) {
	    $start_page = ($page * $per_page_records) - $per_page_records;
    } else {
	    $start_page = 0;
    }
    // postテーブルから3件のデータを取得する
    $sql = 'SELECT * FROM post ORDER BY created_at DESC LIMIT :start_page, :per_page_records';
    $statement = db_conect()->prepare($sql);
    $statement->bindParam(':start_page', $start_page, PDO::PARAM_INT);
    $statement->bindParam(':per_page_records', $per_page_records, PDO::PARAM_INT);
    $statement->execute();
    $records = $statement->fetchAll();
}
    
$statement = null;
 
require_once('function/function.php');
include('views/index.php');