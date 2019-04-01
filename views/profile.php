<?php
    $header_title = 'プロフィール';
    include('views/layouts/header.php');
?>
<body>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    
    <h1>プロフィール</h1>
    <ul>            
        <li>
            名前：  <?php echo h($user['name']) ?><br />
            画像：  <?php if (!empty($user['picture'])) : ?>
                        <img src="userimages/<?php echo h($user['picture']) ?>" width="150" height="150"><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
            一言コメント：
                    <?php if (!empty($user['profile_comment'])) : ?>
                        <?php echo h($user['profile_comment']) ?><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
        </li>
    </ul>
    <?php if ($user['id'] === $_SESSION['user_id']) : ?>
        <a href="edit.php">編集する</a><br />
        <a href="index.php">戻る</a>
    <?php else : ?>
        <a href="index.php">戻る</a>
    <?php endif ?>
</body>
<?php
    include('views/layouts/footer.php');
?>