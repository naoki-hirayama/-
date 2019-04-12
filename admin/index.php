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
$add_color[''] = '選択しない';
$select_color_options = array_merge($add_color, $select_color_options);
//dd($post_repository->fetchAll());exit;
if (isset($_GET['name'], $_GET['comment'], $_GET['color'])) {
    $values = $_GET;
    $errors = [];
    
    if (strlen($values['name']) === 0 && strlen($values['comment']) === 0 && strlen($values['color']) === 0) {
        header('Location: index.php');
        exit;
    } else {
        $result_records = $post_repository->fetchCountByKeywords($values);
    }
    
    if ($result_records > 0) {
        $max_pager_range = 3;
        $per_page_records = 10;
        
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        
        $pager = new Pager($result_records, $max_pager_range, $per_page_records);
        $pager->setCurrentPage($page);
        $offset = $pager->getOffset();
        $per_page_records = $pager->getPerPageRecords();
        $posts = $post_repository->fetchByKeywords($values, $offset, $per_page_records);
    } 
    
} else {
    $max_pager_range = 10;
    $per_page_records = 30;
    $total_records = $post_repository->fetchCount();
    
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    
    $pager = new Pager($total_records, $max_pager_range, $per_page_records);
    $pager->setCurrentPage($page);
    $offset = $pager->getOffset();
    $per_page_records = $pager->getPerPageRecords();
    $posts = $post_repository->fetchByOffSetAndLimit($offset, $per_page_records);
}

if (!empty($posts)) {
    $user_ids = [];
    $post_ids = [];

    foreach ($posts as $post) {
        $post_ids[] = $post['id'];
        if (isset($post['user_id'])) {
            $user_ids[] = $post['user_id'];
        }
    }
    
    if (!empty($user_ids)) {
        $users = $user_repository->fetchByIds($user_ids);
        $user_names = array_column($users, 'name', 'id');
    }
    
    $reply_counts = $reply_repository->fetchCountByPostIds($post_ids);
}

include('../admin/views/index.php');

