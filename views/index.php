<?php
    $header_title = '投稿画面';
    include('views/layouts/header.php');
?>
    <body>
        <h1>投稿画面</h1>
        <!-- エラーメッセージ -->
        <?php  include('views/layouts/errormessage.php'); ?>
        <!-- ここまで -->
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>名前：</p>
            <input type="text" name="name" value="<?php echo $_POST['name'] ?>">
            <p>本文：</p>
            <textarea name="comment" rows="4" cols="20"><?php echo $_POST['comment'] ?></textarea><br />
            <p>画像：</p>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size; ?>" />
            <input type="file" name="picture"><br />
            <select name="color">
                <?php foreach($select_color_options as $key => $value) : ?>
                    <option value="<?php echo $key ?>"<?php echo ($key === $_POST['color']) ? 'selected' : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach ?>
            </select><br />
            <p>削除パスワード:</p>
            <input type="password" name="password"><br />
            <input type="submit" name="submit" value="投稿">
        </form>
        <?php if (empty($errors)) : ?>
            <h2>投稿一覧</h2>
            <p>総投稿数：<?php echo $total_records; ?>件</p>
            <ul>
                <?php if ($records) : ?>
                    <?php foreach ($records as $record) : ?>
                        <li>
                            ID : <?php echo $record['id'] ?><br />
                            名前：<?php echo h($record['name']); ?><br />
                            本文：<font color="<?php echo $record['color'] ?>">
                                      <?php echo h($record['comment']); ?>
                                  </font><br />
                            画像：
                                <?php if (isset($record['picture']) && $record['picture'] !== null) : ?>
                                    <img src="images/<?php echo $record['picture'] ?>" width="300" height="200"><br />
                                <?php else : ?>
                                    なし<br />
                                <?php endif ?>
                            時間：<?php echo $record['created_at'] ?><br />
                             
                            <!--if文でパスワードが設定されていなかったら非表示   -->
                            <?php if (isset($record['password']) && $record['password'] !== null) : ?>
                            <form action="delete.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $record['id'] ?>">
                                <input type="submit" value="削除"/><br />
                            </form>
                            <?php endif ?>    
                            <!--　ここまで　-->
                        
                            ---------------------------------------------<br />
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        <?php endif ?>
        <!--ページング処理-->
        <?php if ($page > 1) : ?>
        <a href="?page=<?php echo $page-1; ?>">前へ</a>
        <?php endif ?>
        
        <?php if ($page <= $left_range) : ?>
        <?php for ($i = 1; $i <= $max_pager_range; $i++) : ?>
        <?php if ($i !== $page) :?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif ?>
        <?php if ($i === $page) :?>
        <a><?php echo $i; ?></a>
        <?php endif ?>
        <?php endfor ?>
        <?php endif ?>
        
        <?php if (($page > $left_range) && ($page < $total_pages - $right_range)) : ?>
        <?php for ($i = $page - $left_range ; $i <= $page + $right_range; $i++) : ?>
        <?php if ($i >= 1): ?>
        <?php if ($i !== $page) :?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif ?>
        <?php if ($i === $page) :?>
        <a><?php echo $i; ?></a>
        <?php endif ?>
        <?php endif ?>
        <?php endfor ?>
        <?php endif ?>
        
        <?php if ($page >= $total_pages - $right_range) : ?>
        <?php for ($i = $total_pages - $max_pager_range + 1; $i <= $total_pages; $i++) : ?>
        <?php if ($i >= 1): ?>
        <?php if ($i !== $page) :?>
        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif ?>
        <?php if ($i === $page) :?>
        <a><?php echo $i; ?></a>
        <?php endif ?>
        <?php endif ?>
        <?php endfor ?>
        <?php endif ?>
        
        <?php if ($page < $total_pages) : ?>
        <a href="?page=<?php echo $page+1; ?>">次へ</a>
        <?php endif ?>
        <!--ここまで-->
    </body>
<?php
    include('views/layouts/footer.php');
?>