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







} else {
//getでアクセスされた時の処理
    //検索機能
    if (isset($_GET['name'], $_GET['comment'])) {
        $values = $_GET;
        $errors = $post_repository->searchValidate($values);
        
        if (empty($errors)) {
            $search_results_are_guest_users = $post_repository->fetchSearchResultsByKeywords($values);
            $name_search_results_in_users = $user_repository->fetchByName($values['name']);
            
            if (!empty($name_search_results_in_users)) {
                $search_user_ids = [];
                foreach ($name_search_results_in_users as $name_search_result_in_users) {
                    $search_user_ids[] = $name_search_result_in_users['id'];
                }
                $search_results_are_having_account_users = $post_repository->fetchSearchResultsByUserIds($values['comment'], $search_user_ids);
            }
            
            if (empty($search_results_are_guest_users) && empty($search_results_are_having_account_users)) {
                $errors[] = '存在しません';
            } else if (empty($search_results_are_guest_users) && !empty($search_results_are_having_account_users)) {
                $searched_posts = $search_results_are_having_account_users;
            } else if (!empty($search_results_are_guest_users) && empty($search_results_are_having_account_users)) {
                $searched_posts = $search_results_are_guest_users;
            } else {
                $searched_posts = array_merge($search_results_are_guest_users, $search_results_are_having_account_users);
            }
        }
    }
    
    $max_pager_range = 10;
    $per_page_records = 30;
    $total_records = $post_repository->fetchCount();
    
    if (!empty($_GET['page'])) {
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

