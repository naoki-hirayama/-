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

if (isset($_GET['name'], $_GET['comment'])) {
    $values = $_GET;
    
    $search_results_name = $post_repository->fetchByName($values['name']);
    $search_results_comment = $post_repository->fetchByComment($values['comment']);
    $search_results_both = $post_repository->fetchByCommentAndName($values);
     
    $searched_posts = $search_results_both;
    
    $user_ids = [];
    $post_ids = [];
    foreach ($searched_posts as $searched_post) {
        $post_ids[] = $searched_post['id'];
        if (isset($searched_post['user_id'])) {
            $user_ids[] = $searched_post['user_id'];
        }
    }
    
    if (!empty($user_ids)) {
        $users = $user_repository->fetchByIds($user_ids);
        $user_names = array_column($users, 'name', 'id');
    }
    dd($post_ids);
    $reply_counts = $reply_repository->fetchCountByPostIds($post_ids);
    dd($reply_counts);exit;
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

