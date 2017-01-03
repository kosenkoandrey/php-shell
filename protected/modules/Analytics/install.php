<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'analytics_set_db_connection': $_SESSION['core']['install']['analytics']['db_connection'] = $_POST['db_connection']; break;
        case 'analytics_set_settings': $_SESSION['core']['install']['analytics']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Analytics</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Costs</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['analytics']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="analytics_set_db_connection">
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

$db = APP::Module('DB')->Open($_SESSION['core']['install']['analytics']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `analytics_yandex_metrika` (
    `id` int(11) UNSIGNED NOT NULL,
    `visit` mediumint(8) NOT NULL,
    `page_views` mediumint(8) NOT NULL,
    `cr_date` date NOT NULL,
    `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `analytics_yandex_metrika`
    ADD PRIMARY KEY (`id`);

    ALTER TABLE `analytics_yandex_metrika`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
");

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `analytics_web_stats` (
    `id` int(11) UNSIGNED NOT NULL,
    `cy` smallint(5) NOT NULL,
    `pr` smallint(5) NOT NULL,
    `cr_date` date NOT NULL,
    `up_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `analytics_web_stats`
    ADD PRIMARY KEY (`id`);

    ALTER TABLE `analytics_web_stats`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_analytics_db_connection', $_SESSION['core']['install']['analytics']['db_connection']);
APP::Module('Registry')->Add('module_analytics_yandex_token', '');
APP::Module('Registry')->Add('module_analytics_yandex_client_id', '');
APP::Module('Registry')->Add('module_analytics_yandex_client_secret', '');
APP::Module('Registry')->Add('module_analytics_yandex_auth_username', '');
APP::Module('Registry')->Add('module_analytics_yandex_auth_password', '');
APP::Module('Registry')->Add('module_analytics_yandex_site', '');
APP::Module('Registry')->Add('module_analytics_yandex_counter', '');

APP::Module('Triggers')->Register('update_analytics_settings',  'Analytics',    'Update analytics settings');
APP::Module('Triggers')->Register('update_analytics_yandex_settings',  'Analytics',    'Update analytics settings');