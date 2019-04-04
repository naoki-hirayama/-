<?php
    $header_title = 'レス一覧';
    include('views/layouts/header.php');
?>
<body>
    <!--ログイン情報-->
    <?php  include('views/layouts/loginuserinfo.php') ?>
    <a href="index.php"　class="btn btn-primary">投稿画面へ戻る</a>
    <h1>ID:<?php echo $post['id'] ?>の投稿</h1>
    <ul>
        <li>
            名前：
                <?php if (!empty($post['user_id'])) : ?>
                    <a href="profile.php?id=<?php echo $post['user_id'] ?>">
                        <?php echo h($current_user_name['name']); ?>
                    </a><br />
                <?php else : ?>
                    <?php echo h($post['name']); ?><br />
                <?php endif ?>
            本文 :
                <font color="<?php echo h($post['color']) ?>">
                    <?php echo h($post['comment']); ?>
                </font><br />
            画像：
                <?php if (!empty($post['picture'])) : ?>
                    <img src="images/<?php echo h($post['picture']) ?>" width="300" height="200"><br />
                <?php else : ?>
                    なし<br />
                <?php endif ?>
            時間：
                <?php echo h($post['created_at']) ?><br />
            ---------------------------------------------<br />
        </li>
    </ul>
    <!--エラーメッセージ-->
    <?php  include('views/layouts/errormessage.php'); ?>
    <h2>レス投稿画面</h2>
    <form action="reply.php?id=<?php echo $post['id'] ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
        <p>名前：<?php echo !empty($_SESSION['user_id']) ? h($user_info['name']) : ''; ?></p>
        <?php if (!empty($_SESSION['user_id'])) : ?>
            <input type="hidden" name="name" value="<?php echo h($user_info['name']) ?>">
        <?php else : ?>
            <input type="text" name="name" value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '' ?>">
        <?php endif ?>
        <p>本文：</p>
        <textarea name="comment" rows="4" cols="20"><?php echo !empty($_POST['comment']) ? $_POST['comment'] : '' ?></textarea><br />
        <p>画像：</p>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
        <input type="file" name="picture"><br />
        <select name="color">
        <?php foreach($select_color_options as $key => $value) : ?>
            <?php if (!empty($_POST['color'])) : ?>
                <option value="<?php echo $key ?>"<?php echo $key === $_POST['color'] ? 'selected' : ''; ?>>
            <?php else : ?>
                <option value="<?php echo $key ?>">
            <?php endif ?>
            <?php echo $value ?>
            </option>
        <?php endforeach ?>
        </select><br />
        <?php if (empty($_SESSION['user_id'])) : ?>
            <p>削除パスワード:</p>
            <input type="password" name="password"><br />
        <?php else : ?>
            <input type="hidden" name="password">
        <?php endif ?>
        <input type="submit" name="submit" value="投稿">
    </form>
    
    <?php if (empty($errors)) : ?>
        <h2>レス一覧</h2>
        <p>総レス数：<?php echo $total_replies ?>件</p>
        <ul>
        <?php if ($reply_posts) : ?>
            <?php foreach ($reply_posts as $reply_post) : ?>
                <li>
                    ID :
                    <?php echo $reply_post['id'] ?><br />
                    名前：
                    <?php if (isset($reply_post['user_id']) && isset($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <?php if ($reply_post['user_id'] === $user['id']) : ?>
                                <a href="profile.php?id=<?php echo $user['id'] ?>"><?php echo h($user['name']) ?></a><br />
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?php echo h($reply_post['name']) ?><br />
                    <?php endif ?>
                    本文：
                    <font color="<?php echo $reply_post['color'] ?>">
                        <?php echo h($reply_post['comment']) ?>
                    </font><br />
                    画像：
                    <?php if (!empty($reply_post['picture'])) : ?>
                        <img src="replyimages/<?php echo h($reply_post['picture']) ?>" width="300" height="200"><br />
                    <?php else : ?>
                        なし<br />
                    <?php endif ?>
                    時間：
                    <?php echo $reply_post['created_at'] ?><br />
                    <!--if文でパスワードが設定されていなかったら非表示   -->
                    <?php if (!empty($reply_post['password'] )) : ?>
                        <form action="deletereply.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $reply_post['id'] ?>">
                            <input type="submit" value="削除"/><br />
                        </form>
                    <?php elseif (isset($reply_post['user_id']) && isset($_SESSION['user_id']) && $reply_post['user_id'] === $_SESSION['user_id']) : ?>
                        <form action="deletereply.php" method ="get">
                            <input type="hidden" name="id" value="<?php echo $reply_post['id'] ?>">
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