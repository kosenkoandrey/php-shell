<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'costs_set_db_connection': $_SESSION['core']['install']['costs']['db_connection'] = $_POST['db_connection']; break;
        case 'costs_set_ssh_connection': $_SESSION['core']['install']['costs']['ssh_connection'] = $_POST['ssh_connection']; break;
        case 'costs_set_settings': $_SESSION['core']['install']['costs']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Costs</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Costs</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['costs']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="costs_set_db_connection">
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
        if (!isset($_SESSION['core']['install']['costs']['ssh_connection'])) {
            $error = true;

            // Build SSH connections array
            $ssh_connections = [];
            $tmp_ssh_connections = APP::Module('Registry')->Get(['module_ssh_connection'], ['id', 'value']);

            foreach (array_key_exists('module_ssh_connection', $tmp_ssh_connections) ? (array) $tmp_ssh_connections['module_ssh_connection'] : [] as $connection) {
                $ssh_connection_value = json_decode($connection['value'], 1);
                $ssh_connections[$connection['id']] = $ssh_connection_value[2] . '@' . $ssh_connection_value[0] . ':' . $ssh_connection_value[1];
            }
            ?>
            <h3>Select SSH connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="costs_set_ssh_connection">
                <select name="ssh_connection">
                    <? foreach ($ssh_connections as $ssh_connection_id => $ssh_connection_name) { ?><option value="<?= $ssh_connection_id ?>"><?= $ssh_connection_name ?></option><? } ?>
                </select>
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['costs']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="costs_set_settings">
                
                <label for="settings[tmp_dir]">Temp dir</label>
                <br>
                <input type="text" name="settings[tmp_dir]" value="/tmp">
                <br><br>
                
                <label for="settings[max_execution_time]">Max execution time</label>
                <br>
                <input type="text" name="settings[max_execution_time]" value="3600">
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

$db = APP::Module('DB')->Open($_SESSION['core']['install']['costs']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `quiz_questions` (
    `id` int(10) UNSIGNED NOT NULL,
    `text` text COLLATE utf8_unicode_ci NOT NULL,
    `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `quiz_questions`
    ADD PRIMARY KEY (`id`);

    ALTER TABLE `quiz_questions`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
    
    CREATE TABLE `quiz_answers` (
    `id` int(10) UNSIGNED NOT NULL,
    `question_id` int(10) UNSIGNED NOT NULL,
    `rating` int(10)  NOT NULL,
    `text` text COLLATE utf8_unicode_ci NOT NULL,
    `correct` tinyint(1) UNSIGNED NOT NULL,
    `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `quiz_answers`
    ADD PRIMARY KEY (`id`);

    ALTER TABLE `quiz_answers`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
    
    CREATE TABLE `quiz_user_answers` (
    `id` int(10) UNSIGNED NOT NULL,
    `user_id` int(10) UNSIGNED NOT NULL,
    `answer_id` int(10) UNSIGNED NOT NULL,
    `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `quiz_user_answers`
    ADD PRIMARY KEY (`id`);

    ALTER TABLE `quiz_user_answers`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_costs_db_connection', $_SESSION['core']['install']['costs']['db_connection']);
APP::Module('Registry')->Add('module_costs_ssh_connection', $_SESSION['core']['install']['costs']['ssh_connection']);
APP::Module('Registry')->Add('module_costs_tmp_dir', $_SESSION['core']['install']['costs']['settings']['tmp_dir']);
APP::Module('Registry')->Add('module_costs_max_execution_time', $_SESSION['core']['install']['costs']['settings']['max_execution_time']);
APP::Module('Registry')->Add('module_costs_yandex_token', '');
APP::Module('Registry')->Add('module_costs_yandex_client_id', '');
APP::Module('Registry')->Add('module_costs_yandex_client_secret', '');
APP::Module('Registry')->Add('module_costs_yandex_utm_source', '');
APP::Module('Registry')->Add('module_costs_yandex_utm_medium', '');
APP::Module('Registry')->Add('module_costs_facebook_token', '');
APP::Module('Registry')->Add('module_costs_facebook_client_id', '');
APP::Module('Registry')->Add('module_costs_facebook_client_secret', '');

APP::Module('Cron')->Add($_SESSION['core']['install']['costs']['ssh_connection'], ['1', '1', '*/1', '*', '*', 'php ' . ROOT . '/init.php Costs GetYandex []']);

APP::Module('Triggers')->Register('download_yandex_costs', 'Costs', 'Download Yandex.Direct costs');
APP::Module('Triggers')->Register('add_manual_cost', 'Costs', 'Add manual cost');
APP::Module('Triggers')->Register('remove_cost', 'Costs', 'Remove cost');
APP::Module('Triggers')->Register('update_cost', 'Costs', 'Update cost');
APP::Module('Triggers')->Register('update_costs_settings', 'Costs', 'Update costs settings');