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
    <!-- エラーメッセージ -->
    <?php  include('../views/layouts/errormessage.php') ?>
    <!--検索結果表示-->
    <h2>検索結果一覧</h2>
    <table border="1">
        <tr>
            <th>投稿ID</th>
            <th>投稿日時</th>
            <th>名前</th>
            <th>本文</th>
            <th>レス数</th>
            <th>編集リンク</th>
            <th>詳細リンク</th>
        </tr>
    <!--ここまで-->
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
            <?php if (isset($post['user_id'])) : ?>
                <td>
                <?php echo h($user_names_are_key_as_user_ids[$post['user_id']]) ?>
                </td>
            <?php else : ?>
                <td>
                <?php echo h($post['name']) ?>
                </td>
            <?php endif ?>
            </td>
            <td>
                <font color="<?php echo $post['color'] ?>">
                    <?php echo h($post['comment']) ?>
                </font>
            </td>
                <?php if (isset($cnts_are_key_as_post_ids) && (in_array($post['id'], $post_ids_have_replies,true))): ?>
                    <td>        
                    <?php echo $cnts_are_key_as_post_ids[$post['id']] ?>件
                    </td>       
                <?php else : ?>  
                    <td>          
                    0件
                    </td>            
                <?php endif ?>
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
                <br>
                <textarea id="input_comment" name="comment" rows="4" cols="20"></textarea><br />
                <!--<img src="../images/posts/"></img>-->
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $picture_max_size ?>">
                <br>
                <input type="file" name="picture"><br />
                <select id="input_color" name="color">
                <?php foreach($select_color_options as $key => $value) : ?>
                    <option value="<?php echo $key ?>"><?php echo $value; ?></option>
                <?php endforeach ?>
                </select>
                <br>
                <input type="submit" value="Submit"/>
                <br>
            </form>
            <p>テキスト</p>
            <button>閉じる</button>
        </div>
    </div>
   
<script type="text/javascript">
    var json_posts = '<?php echo json_encode($posts); ?>';
    console.log(json_posts);
    var hogehoge = [1,2,3,4];
    console.log(hogehoge[0]);
    $(function() {
        $('.show-modal').on('click', function() {
            // console.log(json_posts);
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