<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'social_networks_set_db_connection': $_SESSION['core']['install']['social_networks']['db_connection'] = $_POST['db_connection']; break;
        case 'social_networks_set_ssh_connection': $_SESSION['core']['install']['social_networks']['ssh_connection'] = $_POST['ssh_connection']; break;
        case 'social_networks_set_settings': $_SESSION['core']['install']['social_networks']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install SocialNetworks</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install SocialNetworks</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['social_networks']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="social_networks_set_db_connection">
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
        if (!isset($_SESSION['core']['install']['social_networks']['ssh_connection'])) {
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
                <input type="hidden" name="action" value="social_networks_set_ssh_connection">
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
        if (!isset($_SESSION['core']['install']['social_networks']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="social_networks_set_settings">
                
                <label for="settings[tmp_dir]">Temp dir</label>
                <br>
                <input type="text" name="settings[tmp_dir]" value="/tmp">
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

APP::Module('DB')->Open($_SESSION['core']['install']['social_networks']['db_connection'])->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `social_networks_followers` (
      `id` int(11) NOT NULL,
      `network` enum('vk','fb','gplus','twitter') COLLATE utf8_unicode_ci NOT NULL,
      `count` int(11) NOT NULL,
      `date` date NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    ALTER TABLE `social_networks_followers`
      ADD PRIMARY KEY (`id`),
      ADD KEY `network` (`network`);


    ALTER TABLE `social_networks_followers`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
");

APP::Module('Registry')->Add('module_social_networks_db_connection', $_SESSION['core']['install']['social_networks']['db_connection']);
APP::Module('Registry')->Add('module_social_networks_ssh_connection', $_SESSION['core']['install']['social_networks']['ssh_connection']);
APP::Module('Registry')->Add('module_social_networks_tmp_dir', $_SESSION['core']['install']['social_networks']['settings']['tmp_dir']);
APP::Module('Registry')->Add('module_social_networks_vk_gid', '');
APP::Module('Registry')->Add('module_social_networks_fb_name', '');
APP::Module('Registry')->Add('module_social_networks_gplus_user', '');
APP::Module('Registry')->Add('module_social_networks_gplus_key', '');
APP::Module('Registry')->Add('module_social_networks_twitter_user', '');

APP::Module('Cron')->Add($_SESSION['core']['install']['social_networks']['ssh_connection'], ['0', '0', '*/1', '*', '*', 'php ' . ROOT . '/init.php SocialNetworks UpdateFollowers []']);

APP::Module('Triggers')->Register('social_networks_update_settings', 'Social Networks', 'Update settings');
APP::Module('Triggers')->Register('social_networks_update_other', 'Social Networks', 'Update other');
APP::Module('Triggers')->Register('social_networks_update_followers', 'Social Networks', 'Update followers');