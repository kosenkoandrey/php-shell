<?
$error = false;

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'mail_set_db_connection': $_SESSION['core']['install']['mail']['db_connection'] = $_POST['db_connection']; break;
        case 'mail_set_ssh_connection': $_SESSION['core']['install']['mail']['ssh_connection'] = $_POST['ssh_connection']; break;
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
        if (!isset($_SESSION['core']['install']['mail']['db_connection'])) {
            $error = true;
            ?>
            <h3>Select DB connection</h3>
            <form method="post">
                <input type="hidden" name="action" value="mail_set_db_connection">
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
        if (!isset($_SESSION['core']['install']['mail']['ssh_connection'])) {
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
                <input type="hidden" name="action" value="mail_set_ssh_connection">
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
                
                <label for="settings[tmp_dir]">Temp dir</label>
                <br>
                <input type="text" name="settings[tmp_dir]" value="/tmp">
                <br><br>
                
                <label for="settings[charset]">Charset</label>
                <br>
                <input type="text" name="settings[charset]" value="UTF-8">
                <br><br>
                
                <label for="settings[x_mailer]">X-Mailer</label>
                <br>
                <input type="text" name="settings[x_mailer]" value="php-shell">
                <br><br>
                
                <label for="settings[save_sent_email]">Save sent copies</label>
                <br>
                <select name="settings[save_sent_email]">
                    <option value="1" selected>Enable</option>
                    <option value="">Disable</option>
                </select>
                <br><br>
                
                <label for="settings[sent_email_lifetime]">Lifetime copies of sent emails</label>
                <br>
                <input type="text" name="settings[sent_email_lifetime]" value="1 year">
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

$db = APP::Module('DB')->Open($_SESSION['core']['install']['mail']['db_connection']);

$db->query("
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    SET time_zone = '+00:00';

    CREATE TABLE `mail_copies` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `log` bigint(20) UNSIGNED NOT NULL,
      `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `html` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `plaintext` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_events` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `log` bigint(20) UNSIGNED NOT NULL,
      `user` int(11) UNSIGNED NOT NULL,
      `letter` smallint(6) UNSIGNED NOT NULL,
      `event` enum('delivered','processed','open','click','deferred','bounce','unsubscribe','dropped','spamreport') COLLATE utf8_unicode_ci NOT NULL,
      `details` text COLLATE utf8_unicode_ci,
      `token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
      `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_letters` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `group_id` smallint(5) UNSIGNED NOT NULL,
      `sender` smallint(5) UNSIGNED NOT NULL,
      `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `html` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `plaintext` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `transport` smallint(5) UNSIGNED NOT NULL,
      `priority` smallint(5) NOT NULL DEFAULT '0',
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_letters_groups` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `sub_id` smallint(5) NOT NULL DEFAULT '0',
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_log` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user` int(10) UNSIGNED NOT NULL,
      `letter` smallint(5) UNSIGNED NOT NULL,
      `sender` smallint(5) UNSIGNED NOT NULL,
      `transport` smallint(5) UNSIGNED NOT NULL,
      `state` enum('wait','error','success') COLLATE utf8_unicode_ci NOT NULL,
      `result` text COLLATE utf8_unicode_ci,
      `retries` tinyint(3) UNSIGNED NOT NULL,
      `ping` double UNSIGNED NOT NULL,
      `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_queue` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `log` bigint(20) UNSIGNED NOT NULL,
      `user` int(10) UNSIGNED NOT NULL,
      `letter` smallint(5) UNSIGNED NOT NULL,
      `sender` smallint(5) UNSIGNED NOT NULL,
      `transport` smallint(5) UNSIGNED NOT NULL,
      `sender_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `sender_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `recepient` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `html` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `plaintext` mediumtext COLLATE utf8_unicode_ci NOT NULL,
      `retries` tinyint(3) UNSIGNED NOT NULL,
      `ping` double UNSIGNED NOT NULL,
      `execute` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `priority` smallint(6) NOT NULL,
      `result` text COLLATE utf8_unicode_ci,
      `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_senders` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `group_id` smallint(5) UNSIGNED NOT NULL,
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_senders_groups` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `sub_id` smallint(5) NOT NULL DEFAULT '0',
      `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `mail_transport` (
      `id` smallint(5) UNSIGNED NOT NULL,
      `module` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `method` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `settings` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    ALTER TABLE `mail_copies`
      ADD PRIMARY KEY (`id`),
      ADD KEY `log` (`log`),
      ADD KEY `cr_date` (`cr_date`);

    ALTER TABLE `mail_events`
      ADD PRIMARY KEY (`id`),
      ADD KEY `log` (`log`),
      ADD KEY `user` (`user`),
      ADD KEY `letter` (`letter`);

    ALTER TABLE `mail_letters`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sender` (`sender`),
      ADD KEY `transport` (`transport`),
      ADD KEY `group_id` (`group_id`) USING BTREE;

    ALTER TABLE `mail_letters_groups`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sub_id` (`sub_id`);

    ALTER TABLE `mail_log`
      ADD PRIMARY KEY (`id`),
      ADD KEY `transport` (`transport`),
      ADD KEY `letter` (`letter`),
      ADD KEY `sender` (`sender`),
      ADD KEY `user` (`user`) USING BTREE;

    ALTER TABLE `mail_queue`
      ADD PRIMARY KEY (`id`),
      ADD KEY `log` (`log`),
      ADD KEY `user` (`user`),
      ADD KEY `letter` (`letter`),
      ADD KEY `sender` (`sender`),
      ADD KEY `transport` (`transport`),
      ADD KEY `execute_token_priority` (`execute`,`token`,`priority`) USING BTREE;

    ALTER TABLE `mail_senders`
      ADD PRIMARY KEY (`id`),
      ADD KEY `group_id` (`group_id`);

    ALTER TABLE `mail_senders_groups`
      ADD PRIMARY KEY (`id`),
      ADD KEY `sub_id` (`sub_id`);

    ALTER TABLE `mail_transport`
      ADD PRIMARY KEY (`id`),
      ADD KEY `module_method` (`module`,`method`) USING BTREE;


    ALTER TABLE `mail_copies`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_events`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_letters`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_letters_groups`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_log`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_queue`
      MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_senders`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_senders_groups`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
    ALTER TABLE `mail_transport`
      MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

    ALTER TABLE `mail_copies`
      ADD CONSTRAINT `mail_copies_ibfk_1` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    ALTER TABLE `mail_events`
      ADD CONSTRAINT `mail_events_ibfk_1` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_events_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    ALTER TABLE `mail_letters`
      ADD CONSTRAINT `mail_letters_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_letters_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `mail_letters_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_letters_ibfk_3` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    ALTER TABLE `mail_log`
      ADD CONSTRAINT `mail_log_ibfk_2` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_log_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_log_ibfk_4` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    ALTER TABLE `mail_queue`
      ADD CONSTRAINT `mail_queue_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_queue_ibfk_4` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_queue_ibfk_5` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      ADD CONSTRAINT `mail_queue_ibfk_6` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

    ALTER TABLE `mail_senders`
      ADD CONSTRAINT `mail_senders_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `mail_senders_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$db->query('INSERT INTO `mail_transport` (`id`, `module`, `method`, `settings`, `up_date`) VALUES (NULL, "Mail", "Transport", "admin/mail/settings", NOW())');
$db->query('INSERT INTO `mail_letters_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES (0, 0, "/", NOW())');
$db->query('INSERT INTO `mail_senders_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES (0, 0, "/", NOW())');
$db->query('INSERT INTO `mail_senders` (`id`, `group_id`, `name`, `email`, `up_date`) VALUES (NULL, 0, "' . $_SESSION['core']['install']['mail']['sender']['name'] . '", "' . $_SESSION['core']['install']['mail']['sender']['email'] . '", NOW())');

APP::Module('Registry')->Add('module_mail_db_connection', $_SESSION['core']['install']['mail']['db_connection']);
APP::Module('Registry')->Add('module_mail_ssh_connection', $_SESSION['core']['install']['mail']['ssh_connection']);
APP::Module('Registry')->Add('module_mail_tmp_dir', $_SESSION['core']['install']['mail']['settings']['tmp_dir']);
APP::Module('Registry')->Add('module_mail_charset', $_SESSION['core']['install']['mail']['settings']['charset']);
APP::Module('Registry')->Add('module_mail_x_mailer', $_SESSION['core']['install']['mail']['settings']['x_mailer']);
APP::Module('Registry')->Add('module_mail_save_sent_email', $_SESSION['core']['install']['mail']['settings']['save_sent_email']);
APP::Module('Registry')->Add('module_mail_sent_email_lifetime', $_SESSION['core']['install']['mail']['settings']['sent_email_lifetime']);

APP::Module('Cron')->Add($_SESSION['core']['install']['mail']['ssh_connection'], ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php Mail CopiesGC []']);

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

APP::Module('Triggers')->Register('mail_add_transport', 'Mail / Transport', 'Add');
APP::Module('Triggers')->Register('mail_remove_transport', 'Mail / Transport', 'Remove');
APP::Module('Triggers')->Register('mail_update_transport', 'Mail / Transport', 'Update');

APP::Module('Triggers')->Register('mail_update_settings', 'Mail', 'Update settings');
APP::Module('Triggers')->Register('mail_remove_log_entry', 'Mail', 'Remove log entry');
APP::Module('Triggers')->Register('mail_remove_queue_entry', 'Mail', 'Remove queue entry');

APP::Module('Triggers')->Register('before_mail_send_letter', 'Mail', 'Send mail (before)');
APP::Module('Triggers')->Register('after_mail_send_letter', 'Mail', 'Send mail (after)');

APP::Module('Triggers')->Register('mail_event_processed', 'Mail / Events', 'Processed');
APP::Module('Triggers')->Register('mail_event_delivered', 'Mail / Events', 'Delivered');
APP::Module('Triggers')->Register('mail_event_deferred', 'Mail / Events', 'Deferred');
APP::Module('Triggers')->Register('mail_event_bounce_hard', 'Mail / Events', 'Bounce (hard)');
APP::Module('Triggers')->Register('mail_event_bounce_soft', 'Mail / Events', 'Bounce (soft)');
APP::Module('Triggers')->Register('mail_event_unsubscribe', 'Mail / Events', 'Unsubscribe');
APP::Module('Triggers')->Register('mail_event_spamreport', 'Mail / Events', 'Spamreport');
APP::Module('Triggers')->Register('mail_event_open', 'Mail / Events', 'Open');
APP::Module('Triggers')->Register('mail_event_click', 'Mail / Events', 'Click');