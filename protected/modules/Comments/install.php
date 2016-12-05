<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'comments_set_db_connection': $_SESSION['core']['install']['comments']['db_connection'] = $_POST['db_connection']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Comments</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Comments</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['comments']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="comments_set_db_connection">
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

APP::Module('DB')->Open($_SESSION['core']['install']['comments']['db_connection'])->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `comments_messages` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `sub_id` bigint(20) UNSIGNED NOT NULL,
      `user` int(10) UNSIGNED NOT NULL,
      `object_type` int(10) UNSIGNED NOT NULL,
      `object_id` bigint(20) UNSIGNED NOT NULL,
      `message` text COLLATE utf8_unicode_ci NOT NULL,
      `url` text COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `comments_objects` (
      `id` int(10) UNSIGNED NOT NULL,
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    ALTER TABLE `comments_messages`
      ADD PRIMARY KEY (`id`),
      ADD KEY `user` (`user`),
      ADD KEY `object_type` (`object_type`),
      ADD KEY `object_type_object_id` (`object_type`,`object_id`) USING BTREE;

    ALTER TABLE `comments_objects`
      ADD PRIMARY KEY (`id`);


    ALTER TABLE `comments_messages`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `comments_objects`
      MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

    ALTER TABLE `comments_messages`
      ADD CONSTRAINT `comments_messages_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `comments_messages_ibfk_2` FOREIGN KEY (`object_type`) REFERENCES `comments_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      
    INSERT INTO `comments_objects` (`id`, `name`, `up_date`) VALUES (NULL, 'UserAdmin', CURRENT_TIMESTAMP);
");

APP::Module('Registry')->Add('module_comments_db_connection', $_SESSION['core']['install']['comments']['db_connection']);

APP::Module('Triggers')->Register('comments_add_message', 'Comments', 'Add message');
APP::Module('Triggers')->Register('comments_update_message', 'Comments', 'Update message');
APP::Module('Triggers')->Register('comments_remove_message', 'Comments', 'Remove message');
APP::Module('Triggers')->Register('comments_add_object', 'Comments / Objects', 'Add');
APP::Module('Triggers')->Register('comments_update_object', 'Comments / Objects', 'Update');
APP::Module('Triggers')->Register('comments_remove_object', 'Comments / Objects', 'Remove');
APP::Module('Triggers')->Register('comments_update_settings', 'Comments', 'Update settings');