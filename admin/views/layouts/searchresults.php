<?php if (isset($searched_posts)) : ?>
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
    <?php foreach ($searched_posts as $key => $searched_post) : ?>
    <tr>
        <td>
        <?php echo h($searched_post['id']) ?>
        </td>
        <td>
        <?php echo h($searched_post['created_at']) ?>
        </td>
        <?php if (isset($searched_post['user_id'])) : ?>
            <td>
            <?php echo h($user_names_are_key_as_user_ids[$searched_post['user_id']]) ?>
            </td>
        <?php else : ?>
            <td>
            <?php echo h($searched_post['name']) ?>
            </td>
        <?php endif ?>
        </td>
        <td>
            <font color="<?php echo $searched_post['color'] ?>">
                <?php echo h($searched_post['comment']) ?>
            </font>
        </td>
            <?php if (isset($cnts_are_key_as_post_ids) && (in_array($searched_post['id'], $post_ids_have_replies,true))): ?>
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
            <a href="postdetail.php?id=<?php echo $searched_post['id'] ?>">投稿詳細</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<?php endif ?>