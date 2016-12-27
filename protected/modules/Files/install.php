<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'files_set_db_connection': $_SESSION['core']['install']['files']['db_connection'] = $_POST['db_connection']; break;
        case 'files_set_settings': $_SESSION['core']['install']['files']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Files</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Files</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['files']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="files_set_db_connection">
                <select name="db_connection">
                    <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                </select>
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }

    if (!$error) {
        if (!isset($_SESSION['core']['install']['files']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="files_set_settings">
                
                <label for="settings[module_files_mime]">Mime type</label>
                <br>
                <textarea name="settings[module_files_mime]"></textarea>
                <br><br>

                <label for="settings[module_files_path]">Path</label>
                <br>
                <input type="text" name="settings[module_files_path]" value="/var/www/domains/dev1.sendthis.ru/protected/modules/Files/Storage/">
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

$db = APP::Module('DB')->Open($_SESSION['core']['install']['files']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `files` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `group_id` smallint(5) UNSIGNED NOT NULL,
      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `files_groups` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `sub_id` smallint(5) NOT NULL DEFAULT '0',
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `files`
      ADD PRIMARY KEY (`id`),
      ADD KEY `group_id` (`group_id`) USING BTREE;

    ALTER TABLE `files_groups`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sub_id` (`sub_id`);

    ALTER TABLE `files`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `files_groups`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_files_db_connection', $_SESSION['core']['install']['files']['db_connection']);

APP::Module('Triggers')->Register('files_add', 'Files / Page', 'Add');
APP::Module('Triggers')->Register('files_remove', 'Files / Page', 'Remove');
APP::Module('Triggers')->Register('files_update', 'Files / Page', 'Update');
APP::Module('Triggers')->Register('files_add_group', 'Files / Page', 'Add group');
APP::Module('Triggers')->Register('files_remove_group', 'Files / Page', 'Remove group');
APP::Module('Triggers')->Register('files_update_group', 'Files / Page', 'Update group');

