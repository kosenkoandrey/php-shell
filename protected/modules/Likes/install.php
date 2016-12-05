<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'likes_set_db_connection': $_SESSION['core']['install']['likes']['db_connection'] = $_POST['db_connection']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Likes</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Likes</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['likes']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="likes_set_db_connection">
                <select name="db_connection">
                    <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                </select>
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    ?>
</body>
</html>
<?
$content = ob_get_contents();
ob_end_clean();

if ($error) {
    echo $content;
    exit;
}

// Install module

$data->extractTo(ROOT);

APP::Module('DB')->Open($_SESSION['core']['install']['likes']['db_connection'])->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `likes_list` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user` int(10) UNSIGNED NOT NULL,
      `object_type` int(10) UNSIGNED NOT NULL,
      `object_id` bigint(20) UNSIGNED NOT NULL,
      `url` text COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `likes_objects` (
      `id` int(10) UNSIGNED NOT NULL,
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    ALTER TABLE `likes_list`
      ADD PRIMARY KEY (`id`),
      ADD KEY `user` (`user`),
      ADD KEY `object_type` (`object_type`),
      ADD KEY `object_type_object_id` (`object_type`,`object_id`) USING BTREE;

    ALTER TABLE `likes_objects`
      ADD PRIMARY KEY (`id`);


    ALTER TABLE `likes_list`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `likes_objects`
      MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

    ALTER TABLE `likes_list`
      ADD CONSTRAINT `likes_list_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `likes_list_ibfk_2` FOREIGN KEY (`object_type`) REFERENCES `likes_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    INSERT INTO `likes_objects` (`id`, `name`, `up_date`) VALUES (NULL, 'User', CURRENT_TIMESTAMP);
    INSERT INTO `likes_objects` (`id`, `name`, `up_date`) VALUES (NULL, 'Comment', CURRENT_TIMESTAMP);
");

APP::Module('Registry')->Add('module_likes_db_connection', $_SESSION['core']['install']['likes']['db_connection']);
APP::Module('Registry')->Add(
    'module_users_rule', 
    '["likes\\\/api\\\/toggle\\.json","users\/actions\/login"]', 
    APP::Module('DB')->Select(
        APP::Module('Registry')->conf['connection'], 
        ['fetchColumn', 0], 
        ['id'], 'registry', 
        [
            ['item', '=', 'module_users_role', PDO::PARAM_STR],
            ['value', '=', 'default', PDO::PARAM_STR]
        ]
    )
);

APP::Module('Triggers')->Register('likes_add_like', 'Likes', 'Add like');
APP::Module('Triggers')->Register('likes_remove_like', 'Likes', 'Remove like');
APP::Module('Triggers')->Register('likes_add_object', 'Likes / Objects', 'Add');
APP::Module('Triggers')->Register('likes_update_object', 'Likes / Objects', 'Update');
APP::Module('Triggers')->Register('likes_remove_object', 'Likes / Objects', 'Remove');
APP::Module('Triggers')->Register('likes_update_settings', 'Likes', 'Update settings');