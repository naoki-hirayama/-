<ul>
    <?php if (!empty($errors)) : ?>
        <?php foreach ($errors as $error) : ?>
            <li><?php echo $error ?></li>
        <?php endforeach ?>
    <?php endif ?>
</ul>