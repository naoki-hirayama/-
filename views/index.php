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
            <input type="file" name="picture"><br />
            <select name="color">
                <?php foreach($select_options as $key => $value) : ?>
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
    </body>
<?php
    include('views/layouts/footer.php');
?>