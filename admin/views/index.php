<?php
    $header_title = '管理画面';
    include('../admin/views/layouts/header.php');
?>
    <h1>管理画面</h1>
    <form action="index.php" method="get">
        <p><strong>検索フォーム</strong></p>
        名前：<input type="text" name="name"><br />
        本文：<input type="text" name="comment"><br />
        <select name="color">
            <?php foreach($select_color_options as $key => $value) : ?>
                <option value="<?php echo $key ?>"<?php echo (!empty($_GET['color']) && $key === $_GET['color']) ? 'selected' : ''; ?>>
                    <?php echo $value ?>
                </option>
            <?php endforeach ?>
        </select><br />
        <input type="submit" value="検索"><br />
    </form>
    
    <?php if (isset($result_records)) : ?>  
        <a href="index.php">戻る</a>
        <h2>検索結果<?php echo $result_records ?>件</h2>
        <ul>
            <li>名前：<?php echo ($_GET['name'] !== '') ? $_GET['name'] :'指定なし' ?></li>
            <li>本文：<?php echo ($_GET['comment'] !== '') ? $_GET['comment'] :'指定なし' ?></li>
            <li>色：<?php echo ($_GET['color'] !== '') ? $select_color_options[$_GET['color']]:'指定なし' ?></li>
        </ul>
        <?php if ($result_records == 0) : ?>
            <?php exit; ?>
        <?php endif ?>
    <?php else : ?>
        <h2>投稿一覧</h2>
    <?php endif ?>
    
    <table border="2">
        <tr>
            <th>投稿ID</th>
            <th>投稿日時</th>
            <th>名前</th>
            <th>本文</th>
            <th>レス数</th>
            <th>編集リンク</th>
            <th>詳細リンク</th>
        </tr>
        <?php foreach ($posts as $key => $post) : ?>
        <tr>
            <td>
                <?php echo h($post['id']) ?>
            </td>
            <td>
                <?php echo h($post['created_at']) ?>
            </td>
            <td>
            <?php if (isset($post['user_id'])) : ?>
                <?php echo h($user_names[$post['user_id']]) ?>
            <?php else : ?>
                <?php echo h($post['name']) ?>
            <?php endif ?>
            </td>
            <td>
                <font color="<?php echo $post['color'] ?>">
                    <?php echo h($post['comment']) ?>
                </font>
            </td>
            <td>          
                <?php echo (isset($reply_counts[$post['id']])) ? $reply_counts[$post['id']] : 0 ?>件
            </td>            
            <td>
                <input type="button" id="btn" value="編集" class="show-modal" data-key="<?php echo $key; ?>">
            </td>
            <td>
                <a href="postdetail.php?id=<?php echo $post['id'] ?>">投稿詳細</a>
            </td>
        </tr>
        <?php endforeach ?>
    </table>
    <!--ページング処理-->
    <?php if ($pager->hasPreviousPage()) : ?>
        <a href="<?php echo $pager->createUri($pager->getPreviousPage()) ?>">前へ</a>
    <?php endif ?>
    
    <?php foreach ($pager->getPageNumbers() as $i) : ?>
        <?php if ($i === $pager->getCurrentPage()) : ?>
            <span>
                <?php echo $i ?>
            </span>
        <?php else : ?>
            <a href="<?php echo $pager->createUri($i) ?>">
                <?php echo $i ?>
            </a>
        <?php endif ?>
    <?php endforeach ?>
    
    <?php if ($pager->hasNextPage()) : ?>
        <a href="<?php echo $pager->createUri($pager->getNextPage()) ?>">次へ</a>
    <?php endif ?>
    <!--ここまで-->
    <div id="modalwin" class="modalwin hide">
        <a herf="#" class="modal-close"></a>
        <h1>編集</h1>
        <div class="modalwin-contents">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <input id="input_name" type="text" name="name" value="">
                <br />
                <textarea id="input_comment" name="comment" rows="4" cols="20"></textarea><br />
                <!--<img src="../images/posts/"></img>-->
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
                <br />
                <input type="file" name="picture"><br />
                <select id="input_color" name="color">
                <?php foreach($select_color_options as $key => $value) : ?>
                    <option value="<?php echo $key ?>"><?php echo $value; ?></option>
                <?php endforeach ?>
                </select>
                <br />
                <input type="submit" value="編集"/>
                <br />
            </form>
            <button>閉じる</button>
        </div>
    </div>
   
    <script type="text/javascript">
        var json_posts = '<?php echo json_encode($post); ?>';
        console.log(json_posts);
        $(function() {
            $('.show-modal').on('click', function() {
                console.log($(this).data());
                var key = $(this).data('key');
                console.log(key);
                console.log(json_posts[5]);
                console.log(json_posts[key]);
                $('#input_name').val(json_posts[key].id);
                $('#input_comment').val(json_posts[key].comment);
            });
        });
    </script>
<?php
    include('../admin/views/layouts/footer.php');
?>