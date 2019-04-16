<?php
require_once('../function/db_connect.php');
require_once('../function/Pager.php');
require_once('../function/function.php');
require_once('../models/UserRepository.php');
require_once('../models/PostRepository.php');
require_once('../models/ReplyRepository.php');

$database = db_connect();
$user_repository = new UserRepository($database);
$post_repository = new PostRepository($database);
$reply_repository = new ReplyRepository($database);

$picture_max_size = $user_repository::MAX_PICTURE_SIZE;
$select_color_options = PostRepository::getSelectColorOptions();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = $_POST;

    $errors = $post_repository->validate($_POST);
    
    if (empty($errors)) {
        $post_repository->edit($_POST);
        $response = true;
        echo json_encode($response);
    } else {
        $response =  $errors;
        echo json_encode($response);
    }
}
// * ユーザーの投稿の場合は「名前」はフォームではなくユーザー名をテキストで表示する
// * バリデーションのエラーメッセージはalertで表示する
// * 編集が完了したら「編集しました」とalertと表示し、編集後の内容で一覧に表示される内容を書き換える。
// * ページの再読み込みはせずDOMの書き換えでやる。