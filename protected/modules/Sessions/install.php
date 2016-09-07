<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'sessions_set_connection': $_SESSION['core']['install']['sessions']['connection'] = $_POST['connection']; break;
        case 'sessions_set_settings': $_SESSION['core']['install']['sessions']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Sessions</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Sessions</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['sessions']['connection'])) {
            $error = true;
            ?>
            <h3>Select connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="sessions_set_connection">
                <select name="connection">
                    <? foreach (array_keys(APP::Module('DB')->conf['connections']) as $connection) { ?><option value="<?= $connection ?>"><?= $connection ?></option><? } ?>
                </select>
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['sessions']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="sessions_set_settings">
                <label for="settings[domain]">Cookie domain</label>
                <br>
                <input type="text" name="settings[domain]" value="<?= APP::$conf['location'][1] ?>">
                <br><br>
                <label for="settings[lifetime]">Cookie lifetime</label>
                <br>
                <input type="text" name="settings[lifetime]" value="0">
                <br><br>
                <label for="settings[compress]">Compress</label>
                <br>
                <input type="text" name="settings[compress]" value="9">
                <br><br>
                <label for="settings[gc]">GC maximum lifetime</label>
                <br>
                <input type="text" name="settings[gc]" value="1440">
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

APP::Module('DB')->Open($_SESSION['core']['install']['sessions']['connection'])->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
APP::Module('DB')->Open($_SESSION['core']['install']['sessions']['connection'])->query('SET time_zone = "+00:00";');

if (!APP::Module('DB')->Open($_SESSION['core']['install']['sessions']['connection'])->query('SHOW TABLES LIKE "sessions"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['sessions']['connection'])->query('CREATE TABLE `sessions` (`id` varchar(255) COLLATE utf8_unicode_ci NOT NULL, `touched` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, `data` blob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['sessions']['connection'])->query('ALTER TABLE `sessions` ADD PRIMARY KEY (`id`);');
}

if (!APP::Module('Registry')->Get('module_sessions_cookie_domain')) {
    APP::Module('Registry')->Add('module_sessions_cookie_domain', $_SESSION['core']['install']['sessions']['settings']['domain']);
}

if (!APP::Module('Registry')->Get('module_sessions_cookie_lifetime')) {
    APP::Module('Registry')->Add('module_sessions_cookie_lifetime', $_SESSION['core']['install']['sessions']['settings']['lifetime']);
}

if (!APP::Module('Registry')->Get('module_sessions_compress')) {
    APP::Module('Registry')->Add('module_sessions_compress', $_SESSION['core']['install']['sessions']['settings']['compress']);
}

if (!APP::Module('Registry')->Get('module_sessions_gc_maxlifetime')) {
    APP::Module('Registry')->Add('module_sessions_gc_maxlifetime', $_SESSION['core']['install']['sessions']['settings']['gc']);
}

// Add triggers support
APP::Module('Triggers')->Register('update_sessions_settings', 'Sessions', 'Update settings');

$data->extractTo(ROOT);

$sessions_conf_file = ROOT . '/protected/modules/Sessions/conf.php';
$sessions_conf = preg_replace('/\'connection\' => \'auto\'/', '\'connection\' => \'' . $_SESSION['core']['install']['sessions']['connection']. '\'', file_get_contents($sessions_conf_file));
file_put_contents($sessions_conf_file, $sessions_conf);