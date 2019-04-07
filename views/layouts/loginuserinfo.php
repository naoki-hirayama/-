<?php if (isset($user_info['picture'])) : ?>
    <img src="images/users/<?php echo h($user_info['picture']) ?>" width="50" height="50"><br />
<?php endif ?>
<?php if (isset($_SESSION['user_id'])) : ?>
    <P>ようこそ！<a href="profile.php?id=<?php echo $user_info['id'] ?>"><?php echo h($user_info['name']) ?></a>さん(<?php echo h($user_info['login_id']) ?>)</P>
<?php else : ?>
    <P>ようこそ!ゲストさん</P>
<?php endif ?>
