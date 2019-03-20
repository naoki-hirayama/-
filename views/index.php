<?php
    $header_title = '投稿画面';
    include('views/layouts/header.php');
?>
<body>
    <form action="logout.php" method="get" >
        <input type="submit" name="logout" value="ログアウト">
    </form>
    <p>ようこそ！<?php echo !empty($_SESSION['username']) ? $_SESSION['username'] : 'ゲスト'; ?>さん</p>
    <h1>投稿画面</h1>
    <!-- エラーメッセージ -->
    <?php  include('views/layouts/errormessage.php') ?>
    <!-- ここまで -->
    <form action="index.php" method="post" enctype="multipart/form-data">
        <p>名前：</p>
        <input type="text" name="name" value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '';?>">
        <p>本文：</p>
        <textarea name="comment" rows="4" cols="20"><?php echo !empty($_POST['comment']) ? $_POST['comment'] : '' ?></textarea><br />
        <p>画像：</p>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>" />
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
        <p>削除パスワード:</p>
        <input type="password" name="password"><br />
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
                        名前：<?php echo h($post['name']) ?><br />
                        本文：<font color="<?php echo $post['color'] ?>">
                                  <?php echo h($post['comment']) ?>
                              </font><br />
                        画像：
                            <?php if (isset($post['picture']) && $post['picture'] !== null) : ?>
                                <img src="images/<?php echo $post['picture'] ?>" width="300" height="200"><br />
                            <?php else : ?>
                                なし<br />
                            <?php endif ?>
                        時間：<?php echo $post['created_at'] ?><br />
                         
                        <!--if文でパスワードが設定されていなかったら非表示   -->
                        <?php if (isset($post['password']) && $post['password'] !== null) : ?>
                        <form action="delete.php" method="get">
                            <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                            <input type="submit" value="削除"/><br />
                        </form>
                        <?php endif ?>
                        <!--　ここまで　-->
                    
                        ---------------------------------------------<br />
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
    
        <!--ページング処理-->
        <?php if ($pager->hasPreviousPage()) : ?>
            <a href="?page=<?php echo $pager->getPreviousPage() ?>">前へ</a>
        <?php endif ?>
        
        <?php foreach ($pager->getPageNumbers() as $i) : ?>
            <?php if ($i === $pager->getCurrentPage()) : ?>
                <span>
                    <?php echo $i ?>
                </span>
            <?php else : ?>
                <a href="?page=<?php echo $i ?>">
                    <?php echo $i ?>
                </a>
            <?php endif ?>
        <?php endforeach ?>
        
        <?php if ($pager->hasNextPage()) : ?>           
            <a href="?page=<?php echo $pager->getNextPage() ?>">次へ</a>
        <?php endif ?>
        <!--ここまで-->
    <?php endif ?>
</body>
<?php
    include('views/layouts/footer.php');
?>