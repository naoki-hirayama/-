<?php
session_start();
require_once('function/db_connect.php');
require_once('function/Pager.php');
require_once('function/function.php');
require_once('models/UserRepository.php');

$database = db_connect();
$user_repository = new UserRepository($database);
if (isset($_SESSION['user_id'])) {
    $user_info = $user_repository->fetchById($_SESSION['user_id']);
}
$picture_max_size = $user_repository::MAX_PICTURE_SIZE;
$select_color_options = ['black'=>'黒', 'red'=>'赤', 'blue'=>'青', 'yellow'=>'黄', 'green'=>'緑'];
// POSTでアクセスされたら投稿処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    // バリデーション
    $name = trim(mb_convert_kana($_POST['name'], 's'));
    if (mb_strlen($name, 'UTF-8') === 0) {
        $errors[] = "名前は入力必須です。";
    } else if (mb_strlen($name, 'UTF-8') > 10) {
        $errors[] = "名前は１０文字以内です。";
    } 
    $comment = trim(mb_convert_kana($_POST['comment'], 's'));
    if (mb_strlen($comment, 'UTF-8') === 0) {
        $errors[] = "本文は入力必須です。";
    } else if (mb_strlen($comment, 'UTF-8') > 100) {
        $errors[] = "本文は１００文字以内です。";
    } 
    $color = $_POST['color'];
    if (!array_key_exists($color, $select_color_options)) {
        $errors[] = "文字色が不正です"; 
    }
    $_password = $_POST['password'];
    if (strlen($_password) !== 0) {
        if (mb_strlen($_password, 'UTF-8') < 4) {
            $errors[] = " パスワードは4文字以上です。";
        }
    
        if (!preg_match("/^[a-zA-Z0-9]+$/", $_password)) {
            $errors[] = " パスワードは半角英数字です。";
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
    // 成功した場合はDBへ保存してsend.phpにリダイレクトする
    if (empty($errors)) {
        if (!empty($_FILES['picture']['tmp_name'])) {
            // エラーがなくて画像が投稿された時の画像処理 
            $specific_num = uniqid(mt_rand()); 
            $rename_file = $specific_num.'.'.basename($picture_type);
            $rename_file_path = 'images/'.$rename_file;
            move_uploaded_file($_FILES['picture']['tmp_name'], $rename_file_path);
        }
        //パスワードが入力されない時の処理
        if (strlen($_password) === 0) {
            $password = null;
        } else {
            $password = $_password;
        }
        //ログインユーザーが投稿した時
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            $user_id = null;
        }
        // 画像が投稿されない時の処理
        if (strlen($_FILES['picture']['name']) === 0) {
            $picture = null;
        } else {
            $picture = $rename_file;
        }
        
        $sql = 'INSERT INTO posts (name,comment,color,password,picture,user_id) VALUES (:name,:comment,:color,:password,:picture,:user_id)';
        
        $statement = $database->prepare($sql);
        
        $statement->bindParam(':name', $name);
        $statement->bindParam(':comment', $comment);
        $statement->bindParam(':color', $color);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':picture', $picture);
        $statement->bindParam(':user_id', $user_id);
        
        $statement->execute();
        
        $statement = null;

        header('Location: send.php');
        exit;
    }

} else {
    // GETでアクセスされた時
    $stmt = $database->query('SELECT COUNT(id) AS CNT FROM posts');
    $total_records = $stmt->fetchColumn();
    $max_pager_range = 10;
    $per_page_records = 5;
    
    if (!empty($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    
    $pager = new Pager($total_records, $max_pager_range, $per_page_records);
    $pager->setCurrentPage($page);
    $offset = $pager->getOffset();
    $per_page_records = $pager->getPerPageRecords();
   
    $sql = 'SELECT * FROM posts ORDER BY created_at DESC LIMIT :start_page, :per_page_records';
    $statement = $database->prepare($sql);
    
    $statement->bindParam(':start_page', $offset, PDO::PARAM_INT);
    $statement->bindParam(':per_page_records', $per_page_records, PDO::PARAM_INT);
    
    $statement->execute();
    $posts = $statement->fetchAll();
    
    $user_ids = [];
    foreach ($posts as $post) {
        if (isset($post['user_id'])) {
            $user_ids[] = $post['user_id'];
        }
    }
    
    if (!empty($user_ids)) {
        $ids = implode(',', $user_ids);
        $users = $user_repository->fetchByIds($ids);
    } else {
        $users = null;
    }
}

$statement = null;
include('views/index.php');
