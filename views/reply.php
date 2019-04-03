<?php
    $header_title = 'レス一覧';
    include('views/layouts/header.php');
?>
<body>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <h1>レス一覧</h1>　
    <a href="index.php"　class="btn btn-primary">投稿画面へ</a>
    <ul>
        <li>
            名前：<?php echo h($post['name']); ?><br />
            本文：<font color="<?php echo h($post['color']) ?>">
                        <?php echo h($post['comment']); ?>
                  </font><br />
            画像：
                <?php if (!empty($post['picture'])) : ?>
                    <img src="images/<?php echo h($post['picture']) ?>" width="300" height="200"><br />
                <?php else : ?>
                    なし<br />
                <?php endif ?>
            時間：<?php echo h($post['created_at']) ?><br />
            ---------------------------------------------<br />
        </li>
    </ul>
    <!--エラーメッセージ-->
    <?php  include('views/layouts/errormessage.php'); ?>
    <form action="reply.php" method="post">
        <p>名前：<?php echo !empty($_SESSION['user_id']) ? h($user_info['name']) : ''; ?></p>
        <?php if (!empty($_SESSION['user_id'])) : ?>
            <input type="hidden" name="name" value="<?php echo h($user_info['name']) ?>">
        <?php else : ?>
            <input type="text" name="name" value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '' ?>">
        <?php endif ?>
        <p>コメント：</p>
        <textarea name="comment" rows="4" cols="20"><?php echo !empty($_POST['comment']) ? $_POST['comment'] : '' ?></textarea><br />
        <?php if (empty($_SESSION['user_id'])) : ?>
            <p>削除パスワード:</p>
            <input type="password" name="password"><br />
        <?php else : ?>
            <input type="hidden" name="password">
        <?php endif ?>
        <input type="submit" name="submit" value="投稿">
    </form>
    
    <?php if (empty($errors)) : ?>
        <h2>投稿一覧</h2>
        <p>総投稿数：<?php echo $pager->getTotalRecords() ?>件</p>
        <ul>
        <?php if ($posts) : ?>
            <?php foreach ($posts as $post) : ?>
                <li>
                    ID : <?php echo $post['id'] ?><br />
                    名前：
                    <?php if (isset($post['user_id']) && isset($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <?php if ($post['user_id'] === $user['id']) : ?>
                                <a href="profile.php?id=<?php echo $user['id'] ?>"><?php echo h($user['name']) ?></a><br />
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?php echo h($post['name']) ?><br />
                    <?php endif ?>
                    本文：<font color="<?php echo $post['color'] ?>">
                              <?php echo h($post['comment']) ?>
                          </font><br />
                    時間：<?php echo $post['created_at'] ?><br />
                    <!--if文でパスワードが設定されていなかったら非表示   -->
                    <?php if (!empty($post['password'] )) : ?>
                        <form action="delete.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                            <input type="submit" value="削除"/><br />
                        </form>
                    <?php elseif (isset($post['user_id']) && isset($_SESSION['user_id']) && $post['user_id'] === $_SESSION['user_id']) : ?>
                        <form action="delete.php" method ="get">
                            <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                            <input type="submit" value="ユーザー削除"/><br />
                        </form>
                    <?php endif ?>
                    <!--　ここまで　-->
                
                    ---------------------------------------------<br />
                </li>
            <?php endforeach ?>
        <?php endif ?>
        </ul>
    <?php endif ?>    
</body>

<?php
    include('views/layouts/footer.php');
?>