<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'members_set_db_connection': $_SESSION['core']['install']['members']['db_connection'] = $_POST['db_connection']; break;
        case 'members_set_settings': $_SESSION['core']['install']['members']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Members</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Members</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['members']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="members_set_db_connection">
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

$db = APP::Module('DB')->Open($_SESSION['core']['install']['members']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `members_pages` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `group_id` smallint(5) UNSIGNED NOT NULL,
      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `members_pages_groups` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `sub_id` smallint(5) NOT NULL DEFAULT '0',
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `members_pages`
      ADD PRIMARY KEY (`id`),
      ADD KEY `group_id` (`group_id`) USING BTREE;

    ALTER TABLE `members_pages_groups`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sub_id` (`sub_id`);

    ALTER TABLE `members_pages`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `members_pages_groups`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_members_db_connection', $_SESSION['core']['install']['members']['db_connection']);

APP::Module('Triggers')->Register('members_add_page', 'Members / Page', 'Add');
APP::Module('Triggers')->Register('members_remove_page', 'Members / Page', 'Remove');
APP::Module('Triggers')->Register('members_update_page', 'Members / Page', 'Update');
APP::Module('Triggers')->Register('members_add_page_group', 'Members / Page', 'Add group');
APP::Module('Triggers')->Register('members_remove_page_group', 'Members / Page', 'Remove group');
APP::Module('Triggers')->Register('members_update_page_group', 'Members / Page', 'Update group');

