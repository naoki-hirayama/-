<?php
    $header_title = 'プロフィール';
    include('views/layouts/header.php');
?>
<body>
    <h1>プロフィール</h1>
        <ul>
            <li>
                ログインID : <?php echo h($user['login_id']) ?><br />
                名前：<?php echo h($user['name']) ?><br />
                画像：
                    <?php if (isset($user['picture']) && $user['picture'] !== null) : ?>
                        <img src="images/<?php echo $users['picture'] ?>" width="150" height="150"><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
                一言コメント：<?php echo h($user['comment']) ?><br />
            </li>
        </ul>
    <?php if ($user['id'] === $_SESSION['user_id']) : ?>
    <a href="edit.php?id=<?php echo h($user['id']) ?>">編集する</a><br />
    <a href="index.php">戻る</a>
    <?php else : ?>
    <a href="index.php">戻る</a>
    <?php endif ?>
</body>
<?php
    include('views/layouts/footer.php');
?>