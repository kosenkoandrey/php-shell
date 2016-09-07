<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'mail_set_connection': $_SESSION['core']['install']['mail']['connection'] = $_POST['connection']; break;
        case 'mail_set_sender': $_SESSION['core']['install']['mail']['sender'] = $_POST['sender']; break;
        case 'mail_set_settings': $_SESSION['core']['install']['mail']['settings'] = $_POST['settings']; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Install Mail</title>
    <meta charset="UTF-8">
    <meta name="robots" content="none">
</head>
<body>
    <h1>Install Mail</h1>
    <?
    if (!$error) {
        if (!isset($_SESSION['core']['install']['mail']['connection'])) {
            $error = true;
            ?>
            <h3>Select connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="mail_set_connection">
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
        if (!isset($_SESSION['core']['install']['mail']['sender'])) {
            $error = true;
            ?>
            <h3>Default sender</h3>
            <form method="post">
                <input type="hidden" name="action" value="mail_set_sender">
                <label for="sender[name]">Name</label>
                <br>
                <input type="text" name="sender[name]" value="Admin">
                <br><br>
                <label for="sender[email]">E-Mail</label>
                <br>
                <input type="text" name="sender[email]" value="admin@<?= APP::$conf['location'][1] ?>">
                <br><br>
                <input type="submit" value="Next">
            </form>
            <?
        }
    }
    
    if (!$error) {
        if (!isset($_SESSION['core']['install']['mail']['settings'])) {
            $error = true;
            ?>
            <h3>Settings</h3>
            <form method="post">
                <input type="hidden" name="action" value="mail_set_settings">
                <label for="settings[charset]">Charset</label>
                <br>
                <input type="text" name="settings[charset]" value="UTF-8">
                <br><br>
                <label for="settings[x_mailer]">X-Mailer</label>
                <br>
                <input type="text" name="settings[x_mailer]" value="php-shell">
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

APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SET time_zone = "+00:00";');

if (!APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SHOW TABLES LIKE "letters"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('CREATE TABLE `letters` (`id` smallint(5) UNSIGNED NOT NULL, `group_id` smallint(5) UNSIGNED NOT NULL, `sender_id` smallint(5) UNSIGNED NOT NULL, `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `html` text COLLATE utf8_unicode_ci NOT NULL, `plaintext` text COLLATE utf8_unicode_ci NOT NULL, `list_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `letters` ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`,`sender_id`,`list_id`), ADD KEY `group_id_2` (`group_id`), ADD KEY `sender_id` (`sender_id`);');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `letters` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `letters` ADD CONSTRAINT `letters_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `letters_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `letters_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
}

if (!APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SHOW TABLES LIKE "letters_groups"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('CREATE TABLE `letters_groups` (`id` smallint(5) UNSIGNED NOT NULL, `sub_id` smallint(5) NOT NULL DEFAULT "0", `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('INSERT INTO `letters_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES (0, 0, "/", NOW());');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `letters_groups` ADD PRIMARY KEY (`id`), ADD KEY `sub_id` (`sub_id`);');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `letters_groups` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if (!APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SHOW TABLES LIKE "senders"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('CREATE TABLE `senders` (`id` smallint(5) UNSIGNED NOT NULL, `group_id` smallint(5) UNSIGNED NOT NULL, `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('INSERT INTO `senders` (`id`, `group_id`, `name`, `email`, `up_date`) VALUES (1, 0, "' . $_SESSION['core']['install']['mail']['sender']['name'] . '", "' . $_SESSION['core']['install']['mail']['sender']['email'] . '", NOW())');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `senders` ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`);');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `senders` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `senders` ADD CONSTRAINT `senders_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `senders_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
}

if (!APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('SHOW TABLES LIKE "senders_groups"')->rowCount()) {
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('CREATE TABLE `senders_groups` (`id` smallint(5) UNSIGNED NOT NULL, `sub_id` smallint(5) NOT NULL DEFAULT "0", `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL, `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('INSERT INTO `senders_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES (0, 0, "/", NOW());');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `senders_groups` ADD PRIMARY KEY (`id`), ADD KEY `sub_id` (`sub_id`);');
    APP::Module('DB')->Open($_SESSION['core']['install']['mail']['connection'])->query('ALTER TABLE `senders_groups` MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if (!APP::Module('Registry')->Get('module_mail_charset')) {
    APP::Module('Registry')->Add('module_mail_charset', $_SESSION['core']['install']['mail']['settings']['charset']);
}

if (!APP::Module('Registry')->Get('module_mail_x_mailer')) {
    APP::Module('Registry')->Add('module_mail_x_mailer', $_SESSION['core']['install']['mail']['settings']['x_mailer']);
}

$data->extractTo(ROOT);

$mail_conf_file = ROOT . '/protected/modules/Mail/conf.php';
$mail_conf = preg_replace('/\'connection\' => \'auto\'/', '\'connection\' => \'' . $_SESSION['core']['install']['mail']['connection']. '\'', file_get_contents($mail_conf_file));
file_put_contents($mail_conf_file, $mail_conf);

// Add triggers support
APP::Module('Triggers')->Register('mail_add_letter', 'Mail / Letters', 'Add');
APP::Module('Triggers')->Register('mail_remove_letter', 'Mail / Letters', 'Remove');
APP::Module('Triggers')->Register('mail_update_letter', 'Mail / Letters', 'Update');
APP::Module('Triggers')->Register('mail_add_letters_group', 'Mail / Letters', 'Add group');
APP::Module('Triggers')->Register('mail_remove_letters_group', 'Mail / Letters', 'Remove group');
APP::Module('Triggers')->Register('mail_update_letters_group', 'Mail / Letters', 'Update group');

APP::Module('Triggers')->Register('mail_add_sender', 'Mail / Senders', 'Add');
APP::Module('Triggers')->Register('mail_remove_sender', 'Mail / Senders', 'Remove');
APP::Module('Triggers')->Register('mail_update_sender', 'Mail / Senders', 'Update');
APP::Module('Triggers')->Register('mail_add_senders_group', 'Mail / Senders', 'Add group');
APP::Module('Triggers')->Register('mail_remove_senders_group', 'Mail / Senders', 'Remove group');
APP::Module('Triggers')->Register('mail_update_senders_group', 'Mail / Senders', 'Update group');

APP::Module('Triggers')->Register('mail_update_settings', 'Mail', 'Update settings');
APP::Module('Triggers')->Register('mail_send_letter', 'Mail', 'Send mail');