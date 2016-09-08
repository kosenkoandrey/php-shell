<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'set_connection_settings':
            $_SESSION['core']['install']['cache']['connection'] = [
                'host' => $_POST['host'],
                'port' => $_POST['port']
            ];
            break;
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Cache</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Cache</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['cache']['connection'])) {
            $error = true;
            ?>
            <form method="post">
                <input type="hidden" name="action" value="set_connection_settings">
                
                <label for="host">Host</label>
                <br>
                <input type="text" name="host" value="127.0.0.1">
                <br><br>
                <label for="port">Port</label>
                <br>
                <input type="text" name="port" value="11211">
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

$input = $_SESSION['core']['install']['cache'];

APP::Module('Registry')->Add('module_cache_memcache_host', $input['connection']['host']);
APP::Module('Registry')->Add('module_cache_memcache_port', $input['connection']['port']);

APP::Module('Triggers')->Register('update_cache_settings', 'Cache', 'Update settings');