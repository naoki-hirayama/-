<?php
    $header_title = '管理画面';
    include('../admin/views/layouts/header.php');
?>
    <h1>管理画面</h1>
    <form action="index.php" method="get">
        <p>検索機能(名前と本文)</p>
        名前：<input type="text" name="name"><br />
        本文：<input type="text" name="comment"><br />
            　<input type="submit" value="検索"><br />
    </form>
    <?php if (isset($searched_posts)) : ?>
        <?php if (!empty($searched_posts)) : ?>  
            <?php include('views/layouts/searchresults.php') ?>
        <?php else : ?>
            <?php include('views/layouts/errormessage.php') ?>
            <a href="index.php">戻る</a>
        <?php endif ?>
    <?php else : ?>
    <h2>投稿一覧</h2>
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
        var json_posts = '<?php echo json_encode($posts); ?>';
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
    <?php endif ?> 
<?php
    include('../admin/views/layouts/footer.php');
?>