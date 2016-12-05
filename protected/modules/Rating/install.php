<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'sendthis_set_ssh_connection': 
            $_SESSION['core']['install']['sendthis']['ssh_connection'] = $_POST['ssh_connection']; 
            break;
        case 'sendthis_set_version':
            $_SESSION['core']['install']['sendthis']['version'] = $_POST['version'];
            break;
        case 'sendthis_set_key':
            $_SESSION['core']['install']['sendthis']['key'] = $_POST['key'];
            break;
        case 'sendthis_set_tmp_dir':
            $_SESSION['core']['install']['sendthis']['tmp_dir'] = $_POST['tmp_dir'];
            break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install SendThis</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install SendThis</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['sendthis']['ssh_connection'])) {
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
                <input type="hidden" name="action" value="sendthis_set_ssh_connection">
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
        if (!isset($_SESSION['core']['install']['sendthis']['version'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="sendthis_set_version">
                <label for="version">Version</label>
                <br>
                <input type="text" name="version" value="2.0">
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['sendthis']['key'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="sendthis_set_key">
                <label for="key">Key</label>
                <br>
                <input type="text" name="key" value="">
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['sendthis']['tmp_dir'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="sendthis_set_tmp_dir">
                <label for="tmp_dir">Temp dir</label>
                <br>
                <input type="text" name="tmp_dir" value="/tmp">
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

$db_mail = APP::Module('Mail')->settings['module_mail_db_connection'];

APP::Module('Registry')->Add('module_sendthis_ssh_connection', $_SESSION['core']['install']['sendthis']['ssh_connection']);
APP::Module('Registry')->Add('module_sendthis_version', $_SESSION['core']['install']['sendthis']['version']);
APP::Module('Registry')->Add('module_sendthis_key', $_SESSION['core']['install']['sendthis']['key']);
APP::Module('Registry')->Add('module_sendthis_tmp_dir', $_SESSION['core']['install']['sendthis']['tmp_dir']);

APP::Module('DB')->Open($db_mail)->query('INSERT INTO `mail_transport` (`id`, `module`, `method`, `settings`, `up_date`) VALUES (NULL, "SendThis", "DefaultTransport", "admin/sendthis", NOW())');
APP::Module('DB')->Open($db_mail)->query('INSERT INTO `mail_transport` (`id`, `module`, `method`, `settings`, `up_date`) VALUES (NULL, "SendThis", "DaemonTransport", "admin/sendthis", NOW())');

APP::Module('Cron')->Add($_SESSION['core']['install']['sendthis']['ssh_connection'], ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php SendThis LoadWebhooks []']);

APP::Module('Triggers')->Register('sendthis_webhook', 'SendThis', 'Webhook');
APP::Module('Triggers')->Register('sendthis_update_settings', 'SendThis', 'Update settings');