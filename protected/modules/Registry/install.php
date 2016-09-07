<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'registry_set_connection':
            $_SESSION['core']['install']['registry']['connection'] = $_POST['connection'];
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Registry</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Registry</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['registry']['connection'])) {
            $error = true;
            ?>
            <h3>Select connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="registry_set_connection">
                <select name="connection">
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

APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('SET time_zone = "+00:00";');

if (!APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('SHOW TABLES LIKE "registry"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('CREATE TABLE `registry` (`id` mediumint(9) NOT NULL, `sub_id` mediumint(5) UNSIGNED NOT NULL, `item` varchar(150) COLLATE utf8_unicode_ci NOT NULL, `value` text COLLATE utf8_unicode_ci NOT NULL, `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('ALTER TABLE `registry` ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`item`);');
    APP::Module('DB')->Open($_SESSION['core']['install']['registry']['connection'])->query('ALTER TABLE `registry` MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

$registry_conf_file = ROOT . '/protected/modules/Registry/conf.php';
$registry_conf = preg_replace('/\'connection\' => \'auto\'/', '\'connection\' => \'' . $_SESSION['core']['install']['registry']['connection']. '\'', file_get_contents($registry_conf_file));
file_put_contents($registry_conf_file, $registry_conf);