SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `blog_articles` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `group_id` smallint(5) UNSIGNED NOT NULL,
  `uri` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `h1_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` text COLLATE utf8_unicode_ci NOT NULL,
  `html_content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `image_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `robots` enum('all','none','index,follow','noindex,follow','index,nofollow','noindex,nofollow') COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `blog_groups` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `sub_id` smallint(5) NOT NULL DEFAULT '0',
  `uri` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `h1_title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `annotation` text COLLATE utf8_unicode_ci NOT NULL,
  `image_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `robots` enum('all','none','index,follow','noindex,follow','index,nofollow','noindex,nofollow') COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `blog_groups` (`id`, `sub_id`, `uri`, `page_title`, `h1_title`, `annotation`, `image_type`, `image`, `description`, `keywords`, `robots`, `up_date`) VALUES
(0, 0, '', '/', '', '', '', '', '', '', '', CURRENT_TIMESTAMP);

CREATE TABLE `comments_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `object_type` int(10) UNSIGNED NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `comments_objects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `comments_objects` (`id`, `name`, `up_date`) VALUES
(1, 'UserAdmin', CURRENT_TIMESTAMP),
(2, 'BlogArticle', CURRENT_TIMESTAMP);

CREATE TABLE `likes_list` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `object_type` int(10) UNSIGNED NOT NULL,
  `object_id` bigint(20) UNSIGNED NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `likes_objects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `likes_objects` (`id`, `name`, `up_date`) VALUES
(1, 'User', CURRENT_TIMESTAMP),
(2, 'Comment', CURRENT_TIMESTAMP),
(3, 'BlogArticle', CURRENT_TIMESTAMP);

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

INSERT INTO `mail_letters` (`id`, `group_id`, `sender`, `subject`, `html`, `plaintext`, `transport`, `priority`, `up_date`) VALUES
(1, 1, 1, 'Welcome', '<h1>Welcome</h1>\r\n\r\nLogin page:\r\n<br>\r\n<a href="<?= APP::Module(\'Routing\')->root ?>users/actions/login"><?= APP::Module(\'Routing\')->root ?>users/actions/login</a>\r\n<br>\r\n\r\n<h2>Login details</h2>\r\n<table border="0" cellpadding="3" width="300">\r\n<tr>\r\n<td>E-Mail</td>\r\n<td><strong><?= $data[\'email\'] ?></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Password</td>\r\n<td><strong><?= $data[\'password\'] ?></strong></td>\r\n</tr>\r\n</table>\r\n<br>\r\n\r\n<h2>Warning!</h2>\r\nYou must confirm your E-Mail address until <?= strftime(\'%e %B %Y\', $data[\'expire\']) ?>.\r\n<br>\r\nFollow the link to confirm your E-Mail address: <a href="<?= $data[\'link\'] ?>" target="_blank"><?= $data[\'link\'] ?></a>', 'Welcome\r\n\r\nLogin page\r\n--------------------\r\n<?= APP::Module(\'Routing\')->root ?>users/actions/login\r\n\r\nLogin details\r\n--------------------\r\nE-Mail: <?= $data[\'email\'] ?>\r\nPassword: <?= $data[\'password\'] ?>\r\n\r\nWarning!\r\n--------------------\r\nYou must confirm your E-Mail address until <?= strftime(\'%e %B %Y\', $data[\'expire\']) ?>.\r\n\r\nFollow the link to confirm your E-Mail address:\r\n<?= $data[\'link\'] ?>', 1, 100, CURRENT_TIMESTAMP),
(2, 1, 1, 'Welcome', '<h1>Welcome</h1>\r\n\r\nLogin page:\r\n<br>\r\n<a href="<?= APP::Module(\'Routing\')->root ?>users/actions/login"><?= APP::Module(\'Routing\')->root ?>users/actions/login</a>\r\n<br>\r\n\r\n<h2>Login details</h2>\r\n<table border="0" cellpadding="3" width="300">\r\n<tr>\r\n<td>E-Mail</td>\r\n<td><strong><?= $data[\'email\'] ?></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Password</td>\r\n<td><strong><?= $data[\'password\'] ?></strong></td>\r\n</tr>\r\n</table>\r\n<br>\r\n\r\n<h2>Warning!</h2>\r\nYou must confirm your E-Mail address until <?= strftime(\'%e %B %Y\', $data[\'expire\']) ?>.\r\n<br>\r\nFollow the link to confirm your E-Mail address: <a href="<?= $data[\'link\'] ?>" target="_blank"><?= $data[\'link\'] ?></a>', 'Welcome\r\n\r\nLogin page\r\n--------------------\r\n<?= APP::Module(\'Routing\')->root ?>users/actions/login\r\n\r\nLogin details\r\n--------------------\r\nE-Mail: <?= $data[\'email\'] ?>\r\nPassword: <?= $data[\'password\'] ?>\r\n\r\nWarning!\r\n--------------------\r\nYou must confirm your E-Mail address until <?= strftime(\'%e %B %Y\', $data[\'expire\']) ?>.\r\n\r\nFollow the link to confirm your E-Mail address:\r\n<?= $data[\'link\'] ?>', 1, 100, CURRENT_TIMESTAMP),
(3, 1, 1, 'Reset password', '<h1>Reset password</h1>\r\n\r\nFollow the link to set new password: <a href="<?= $data[\'link\'] ?>" target="_blank"><?= $data[\'link\'] ?></a>', 'Reset password\r\n\r\nFollow the link to set new password: <?= $data[\'link\'] ?>', 1, 100, CURRENT_TIMESTAMP),
(4, 1, 1, 'Password successfully changed', '<h1>Password successfully changed</h1>\r\n\r\nLogin page:\r\n<br>\r\n<a href="<?= APP::Module(\'Routing\')->root ?>users/actions/login"><?= APP::Module(\'Routing\')->root ?>users/actions/login</a>\r\n<br>\r\n\r\n<h2>Login details</h2>\r\n<table border="0" cellpadding="3" width="300">\r\n<tr>\r\n<td>E-Mail</td>\r\n<td><strong><?= $data[\'email\'] ?></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Password</td>\r\n<td><strong><?= $data[\'password\'] ?></strong></td>\r\n</tr>\r\n</table>', 'Password successfully changed\r\n\r\nLogin page\r\n--------------------\r\n<?= APP::Module(\'Routing\')->root ?>users/actions/login\r\n\r\nLogin details\r\n--------------------\r\nE-Mail: <?= $data[\'email\'] ?>\r\nPassword: <?= $data[\'password\'] ?>', 1, 100, CURRENT_TIMESTAMP),
(5, 1, 1, 'Welcome', '<h1>Welcome</h1>\r\n\r\nLogin page:\r\n<br>\r\n<a href="<?= APP::Module(\'Routing\')->root ?>users/actions/login"><?= APP::Module(\'Routing\')->root ?>users/actions/login</a>\r\n<br>\r\n\r\n<h2>Login details</h2>\r\n<table border="0" cellpadding="3" width="300">\r\n<tr>\r\n<td>E-Mail</td>\r\n<td><strong><?= $data[\'email\'] ?></strong></td>\r\n</tr>\r\n<tr>\r\n<td>Password</td>\r\n<td><strong><?= $data[\'password\'] ?></strong></td>\r\n</tr>\r\n</table>', 'Welcome\r\n\r\nLogin page\r\n--------------------\r\n<?= APP::Module(\'Routing\')->root ?>users/actions/login\r\n\r\nLogin details\r\n--------------------\r\nE-Mail: <?= $data[\'email\'] ?>\r\nPassword: <?= $data[\'password\'] ?>', 1, 100, CURRENT_TIMESTAMP);

CREATE TABLE `mail_letters_groups` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `sub_id` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `mail_letters_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES
(0, 0, '/', CURRENT_TIMESTAMP),
(1, 0, 'Users', CURRENT_TIMESTAMP);

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

INSERT INTO `mail_senders` (`id`, `group_id`, `name`, `email`, `up_date`) VALUES
(1, 0, 'Admin', 'admin@phpshell', CURRENT_TIMESTAMP);

CREATE TABLE `mail_senders_groups` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `sub_id` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `mail_senders_groups` (`id`, `sub_id`, `name`, `up_date`) VALUES
(0, 0, '/', CURRENT_TIMESTAMP);

CREATE TABLE `mail_transport` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `module` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `settings` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `mail_transport` (`id`, `module`, `method`, `settings`, `up_date`) VALUES
(1, 'Mail', 'Transport', 'admin/mail/settings', CURRENT_TIMESTAMP),
(2, 'SendThis', 'DefaultTransport', 'admin/sendthis', CURRENT_TIMESTAMP),
(3, 'SendThis', 'DaemonTransport', 'admin/sendthis', CURRENT_TIMESTAMP);

CREATE TABLE `registry` (
  `id` mediumint(9) NOT NULL,
  `sub_id` mediumint(5) UNSIGNED NOT NULL,
  `item` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `registry` (`id`, `sub_id`, `item`, `value`, `up_date`) VALUES
(1, 0, 'module_crypt_key', 'eadec977f4afda1c819f847620d3aaab', CURRENT_TIMESTAMP),
(2, 0, 'module_trigger_type', '["add_trigger", "Triggers", "Add trigger"]', CURRENT_TIMESTAMP),
(3, 0, 'module_trigger_type', '["remove_trigger", "Triggers", "Remove trigger"]', CURRENT_TIMESTAMP),
(4, 0, 'module_trigger_type', '["update_trigger", "Triggers", "Update trigger"]', CURRENT_TIMESTAMP),
(5, 0, 'module_ssh_connection', '["127.0.0.1","22","root",""]', CURRENT_TIMESTAMP),
(6, 0, 'module_trigger_type', '["add_ssh_connection", "SSH", "Add connection"]', CURRENT_TIMESTAMP),
(7, 0, 'module_trigger_type', '["remove_ssh_connection", "SSH", "Remove connection"]', CURRENT_TIMESTAMP),
(8, 0, 'module_trigger_type', '["update_ssh_connection", "SSH", "Update connection"]', CURRENT_TIMESTAMP),
(9, 0, 'module_sessions_db_connection', 'auto', CURRENT_TIMESTAMP),
(10, 0, 'module_sessions_cookie_domain', 'domain.com', CURRENT_TIMESTAMP),
(11, 0, 'module_sessions_cookie_lifetime', '0', CURRENT_TIMESTAMP),
(12, 0, 'module_sessions_compress', '9', CURRENT_TIMESTAMP),
(13, 0, 'module_sessions_gc_maxlifetime', '1440', CURRENT_TIMESTAMP),
(14, 0, 'module_trigger_type', '["update_sessions_settings", "Sessions", "Update settings"]', CURRENT_TIMESTAMP),
(15, 0, 'module_cron_tmp_file', '/tmp/crontab', CURRENT_TIMESTAMP),
(16, 0, 'module_trigger_type', '["add_cron_job", "Cron", "Add job"]', CURRENT_TIMESTAMP),
(17, 0, 'module_trigger_type', '["update_cron_job", "Cron", "Update job"]', CURRENT_TIMESTAMP),
(18, 0, 'module_trigger_type', '["remove_cron_job", "Cron", "Remove job"]', CURRENT_TIMESTAMP),
(19, 0, 'module_trigger_type', '["update_cron_settings", "Cron", "Update settings"]', CURRENT_TIMESTAMP),
(20, 0, 'module_mail_db_connection', 'auto', CURRENT_TIMESTAMP),
(21, 0, 'module_mail_ssh_connection', '5', CURRENT_TIMESTAMP),
(22, 0, 'module_mail_tmp_dir', '/tmp', CURRENT_TIMESTAMP),
(23, 0, 'module_mail_charset', 'UTF-8', CURRENT_TIMESTAMP),
(24, 0, 'module_mail_x_mailer', 'php-shell', CURRENT_TIMESTAMP),
(25, 0, 'module_mail_save_sent_email', '1', CURRENT_TIMESTAMP),
(26, 0, 'module_mail_sent_email_lifetime', '1 year', CURRENT_TIMESTAMP),
(27, 5, 'module_cron_job', '*/1 * * * * php init.php Mail CopiesGC []', CURRENT_TIMESTAMP),
(28, 0, 'module_trigger_type', '["mail_add_letter", "Mail / Letters", "Add"]', CURRENT_TIMESTAMP),
(29, 0, 'module_trigger_type', '["mail_remove_letter", "Mail / Letters", "Remove"]', CURRENT_TIMESTAMP),
(30, 0, 'module_trigger_type', '["mail_update_letter", "Mail / Letters", "Update"]', CURRENT_TIMESTAMP),
(31, 0, 'module_trigger_type', '["mail_add_letters_group", "Mail / Letters", "Add group"]', CURRENT_TIMESTAMP),
(32, 0, 'module_trigger_type', '["mail_remove_letters_group", "Mail / Letters", "Remove group"]', CURRENT_TIMESTAMP),
(33, 0, 'module_trigger_type', '["mail_update_letters_group", "Mail / Letters", "Update group"]', CURRENT_TIMESTAMP),
(34, 0, 'module_trigger_type', '["mail_add_sender", "Mail / Senders", "Add"]', CURRENT_TIMESTAMP),
(35, 0, 'module_trigger_type', '["mail_remove_sender", "Mail / Senders", "Remove"]', CURRENT_TIMESTAMP),
(36, 0, 'module_trigger_type', '["mail_update_sender", "Mail / Senders", "Update"]', CURRENT_TIMESTAMP),
(37, 0, 'module_trigger_type', '["mail_add_senders_group", "Mail / Senders", "Add group"]', CURRENT_TIMESTAMP),
(38, 0, 'module_trigger_type', '["mail_remove_senders_group", "Mail / Senders", "Remove group"]', CURRENT_TIMESTAMP),
(39, 0, 'module_trigger_type', '["mail_update_senders_group", "Mail / Senders", "Update group"]', CURRENT_TIMESTAMP),
(40, 0, 'module_trigger_type', '["mail_add_transport", "Mail / Transport", "Add"]', CURRENT_TIMESTAMP),
(41, 0, 'module_trigger_type', '["mail_remove_transport", "Mail / Transport", "Remove"]', CURRENT_TIMESTAMP),
(42, 0, 'module_trigger_type', '["mail_update_transport", "Mail / Transport", "Update"]', CURRENT_TIMESTAMP),
(43, 0, 'module_trigger_type', '["mail_update_settings", "Mail", "Update settings"]', CURRENT_TIMESTAMP),
(44, 0, 'module_trigger_type', '["mail_remove_log_entry", "Mail", "Remove log entry"]', CURRENT_TIMESTAMP),
(45, 0, 'module_trigger_type', '["mail_remove_queue_entry", "Mail", "Remove queue entry"]', CURRENT_TIMESTAMP),
(46, 0, 'module_trigger_type', '["before_mail_send_letter", "Mail", "Send mail (before)"]', CURRENT_TIMESTAMP),
(47, 0, 'module_trigger_type', '["after_mail_send_letter", "Mail", "Send mail (after)"]', CURRENT_TIMESTAMP),
(48, 0, 'module_trigger_type', '["mail_event_processed", "Mail / Events", "Processed"]', CURRENT_TIMESTAMP),
(49, 0, 'module_trigger_type', '["mail_event_delivered", "Mail / Events", "Delivered"]', CURRENT_TIMESTAMP),
(50, 0, 'module_trigger_type', '["mail_event_deferred", "Mail / Events", "Deferred"]', CURRENT_TIMESTAMP),
(51, 0, 'module_trigger_type', '["mail_event_bounce_hard", "Mail / Events", "Bounce (hard)"]', CURRENT_TIMESTAMP),
(52, 0, 'module_trigger_type', '["mail_event_bounce_soft", "Mail / Events", "Bounce (soft)"]', CURRENT_TIMESTAMP),
(53, 0, 'module_trigger_type', '["mail_event_unsubscribe", "Mail / Events", "Unsubscribe"]', CURRENT_TIMESTAMP),
(54, 0, 'module_trigger_type', '["mail_event_spamreport", "Mail / Events", "Spamreport"]', CURRENT_TIMESTAMP),
(55, 0, 'module_trigger_type', '["mail_event_open", "Mail / Events", "Open"]', CURRENT_TIMESTAMP),
(56, 0, 'module_trigger_type', '["mail_event_click", "Mail / Events", "Click"]', CURRENT_TIMESTAMP),
(57, 0, 'module_users_register_activation_letter', '1', CURRENT_TIMESTAMP),
(58, 0, 'module_users_subscribe_activation_letter', '2', CURRENT_TIMESTAMP),
(59, 0, 'module_users_reset_password_letter', '3', CURRENT_TIMESTAMP),
(60, 0, 'module_users_change_password_letter', '4', CURRENT_TIMESTAMP),
(61, 0, 'module_users_register_letter', '5', CURRENT_TIMESTAMP),
(62, 0, 'module_users_role', 'default', CURRENT_TIMESTAMP),
(63, 0, 'module_users_role', 'new', CURRENT_TIMESTAMP),
(64, 0, 'module_users_role', 'user', CURRENT_TIMESTAMP),
(65, 0, 'module_users_role', 'admin', CURRENT_TIMESTAMP),
(66, 62, 'module_users_rule', '["users\\\\/actions\\\\/change-password(.*)","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(67, 62, 'module_users_rule', '["users\\\\/profile","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(68, 62, 'module_users_rule', '["users\\\\/api\\\\/about\\\\/update\\\\.json","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(69, 62, 'module_users_rule', '["admin(.*)","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(70, 63, 'module_users_rule', '["admin(.*)","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(71, 64, 'module_users_rule', '["admin(.*)","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(72, 0, 'module_users_db_connection', 'auto', CURRENT_TIMESTAMP),
(73, 0, 'module_users_ssh_connection', '5', CURRENT_TIMESTAMP),
(74, 0, 'module_users_auth_token', '1', CURRENT_TIMESTAMP),
(75, 0, 'module_users_check_rules', '1', CURRENT_TIMESTAMP),
(76, 0, 'module_users_gen_pass_length', '6', CURRENT_TIMESTAMP),
(77, 0, 'module_users_min_pass_length', '3', CURRENT_TIMESTAMP),
(78, 0, 'module_users_login_service', '1', CURRENT_TIMESTAMP),
(79, 0, 'module_users_register_service', '1', CURRENT_TIMESTAMP),
(80, 0, 'module_users_change_password_service', '1', CURRENT_TIMESTAMP),
(81, 0, 'module_users_reset_password_service', '1', CURRENT_TIMESTAMP),
(82, 0, 'module_users_oauth_client_fb_id', '0', CURRENT_TIMESTAMP),
(83, 0, 'module_users_oauth_client_fb_key', '0', CURRENT_TIMESTAMP),
(84, 0, 'module_users_oauth_client_google_id', '0', CURRENT_TIMESTAMP),
(85, 0, 'module_users_oauth_client_google_key', '0', CURRENT_TIMESTAMP),
(86, 0, 'module_users_oauth_client_vk_id', '0', CURRENT_TIMESTAMP),
(87, 0, 'module_users_oauth_client_vk_key', '0', CURRENT_TIMESTAMP),
(88, 0, 'module_users_oauth_client_ya_id', '0', CURRENT_TIMESTAMP),
(89, 0, 'module_users_oauth_client_ya_key', '0', CURRENT_TIMESTAMP),
(90, 0, 'module_users_timeout_activation', '1 week', CURRENT_TIMESTAMP),
(91, 0, 'module_users_timeout_email', '1 year', CURRENT_TIMESTAMP),
(92, 0, 'module_users_timeout_token', '1 year', CURRENT_TIMESTAMP),
(93, 0, 'module_users_tmp_dir', '/tmp', CURRENT_TIMESTAMP),
(94, 0, 'module_users_profile_picture', 'public/ui/img/profile-pics/default.png', CURRENT_TIMESTAMP),
(95, 5, 'module_cron_job', '*/1 * * * * php init.php Users NewUsersGC []', CURRENT_TIMESTAMP),
(96, 0, 'module_trigger_type', '["user_logout", "Users", "Logout"]', CURRENT_TIMESTAMP),
(97, 0, 'module_trigger_type', '["user_activate", "Users", "Activate"]', CURRENT_TIMESTAMP),
(98, 0, 'module_trigger_type', '["remove_user", "Users", "Remove"]', CURRENT_TIMESTAMP),
(99, 0, 'module_trigger_type', '["add_user", "Users", "Add"]', CURRENT_TIMESTAMP),
(100, 0, 'module_trigger_type', '["user_login", "Users", "Login"]', CURRENT_TIMESTAMP),
(101, 0, 'module_trigger_type', '["user_double_login", "Users", "Double login"]', CURRENT_TIMESTAMP),
(102, 0, 'module_trigger_type', '["register_user", "Users", "Register"]', CURRENT_TIMESTAMP),
(103, 0, 'module_trigger_type', '["subscribe_user", "Users", "Subscribe"]', CURRENT_TIMESTAMP),
(104, 0, 'module_trigger_type', '["reset_user_password", "Users", "Reset password"]', CURRENT_TIMESTAMP),
(105, 0, 'module_trigger_type', '["change_user_password", "Users", "Change password"]', CURRENT_TIMESTAMP),
(106, 0, 'module_trigger_type', '["update_user", "Users", "Update"]', CURRENT_TIMESTAMP),
(107, 0, 'module_trigger_type', '["update_about_user", "Users", "Update about"]', CURRENT_TIMESTAMP),
(108, 0, 'module_trigger_type', '["add_user_role", "Users / Roles", "Add"]', CURRENT_TIMESTAMP),
(109, 0, 'module_trigger_type', '["remove_user_role", "Users / Roles", "Remove"]', CURRENT_TIMESTAMP),
(110, 0, 'module_trigger_type', '["add_user_rule", "Users / Rules", "Add"]', CURRENT_TIMESTAMP),
(111, 0, 'module_trigger_type', '["remove_user_rule", "Users / Rules", "Remove"]', CURRENT_TIMESTAMP),
(112, 0, 'module_trigger_type', '["update_user_rule", "Users / Rules", "Update"]', CURRENT_TIMESTAMP),
(113, 0, 'module_trigger_type', '["update_users_oauth_settings", "Users / Settings", "Update OAuth"]', CURRENT_TIMESTAMP),
(114, 0, 'module_trigger_type', '["update_users_notifications_settings", "Users / Settings", "Update notifications"]', CURRENT_TIMESTAMP),
(115, 0, 'module_trigger_type', '["update_users_services_settings", "Users / Settings", "Update services"]', CURRENT_TIMESTAMP),
(116, 0, 'module_trigger_type', '["update_users_auth_settings", "Users / Settings", "Update auth"]', CURRENT_TIMESTAMP),
(117, 0, 'module_trigger_type', '["update_users_passwords_settings", "Users / Settings", "Update passwords"]', CURRENT_TIMESTAMP),
(118, 0, 'module_trigger_type', '["update_users_timeouts_settings", "Users / Settings", "Update timeouts"]', CURRENT_TIMESTAMP),
(119, 0, 'module_trigger_type', '["update_users_other_settings", "Users / Settings", "Update other"]', CURRENT_TIMESTAMP),
(120, 0, 'module_trigger_type', '["import_locale_module", "Admin", "Import locale module"]', CURRENT_TIMESTAMP),
(121, 0, 'module_trigger_type', '["remove_imported_module", "Admin", "Remove imported module"]', CURRENT_TIMESTAMP),
(122, 0, 'module_trigger_type', '["export_module", "Admin", "Export module"]', CURRENT_TIMESTAMP),
(123, 0, 'module_trigger_type', '["uninstall_module", "Admin", "Uninstall module"]', CURRENT_TIMESTAMP),
(124, 0, 'module_comments_db_connection', 'auto', CURRENT_TIMESTAMP),
(125, 0, 'module_trigger_type', '["comments_add_message", "Comments", "Add message"]', CURRENT_TIMESTAMP),
(126, 0, 'module_trigger_type', '["comments_update_message", "Comments", "Update message"]', CURRENT_TIMESTAMP),
(127, 0, 'module_trigger_type', '["comments_remove_message", "Comments", "Remove message"]', CURRENT_TIMESTAMP),
(128, 0, 'module_trigger_type', '["comments_add_object", "Comments / Objects", "Add"]', CURRENT_TIMESTAMP),
(129, 0, 'module_trigger_type', '["comments_update_object", "Comments / Objects", "Update"]', CURRENT_TIMESTAMP),
(130, 0, 'module_trigger_type', '["comments_remove_object", "Comments / Objects", "Remove"]', CURRENT_TIMESTAMP),
(131, 0, 'module_trigger_type', '["comments_update_settings", "Comments", "Update settings"]', CURRENT_TIMESTAMP),
(132, 0, 'module_likes_db_connection', 'auto', CURRENT_TIMESTAMP),
(133, 62, 'module_users_rule', '["likes\\\\/api\\\\/toggle\\.json","users\\/actions\\/login"]', CURRENT_TIMESTAMP),
(134, 0, 'module_trigger_type', '["likes_add_like", "Likes", "Add like"]', CURRENT_TIMESTAMP),
(135, 0, 'module_trigger_type', '["likes_remove_like", "Likes", "Remove like"]', CURRENT_TIMESTAMP),
(136, 0, 'module_trigger_type', '["likes_add_object", "Likes / Objects", "Add"]', CURRENT_TIMESTAMP),
(137, 0, 'module_trigger_type', '["likes_update_object", "Likes / Objects", "Update"]', CURRENT_TIMESTAMP),
(138, 0, 'module_trigger_type', '["likes_remove_object", "Likes / Objects", "Remove"]', CURRENT_TIMESTAMP),
(139, 0, 'module_trigger_type', '["likes_update_settings", "Likes", "Update settings"]', CURRENT_TIMESTAMP),
(140, 0, 'module_social_networks_db_connection', 'auto', CURRENT_TIMESTAMP),
(141, 0, 'module_social_networks_ssh_connection', '5', CURRENT_TIMESTAMP),
(142, 0, 'module_social_networks_tmp_dir', '/tmp', CURRENT_TIMESTAMP),
(143, 0, 'module_social_networks_vk_gid', '0', CURRENT_TIMESTAMP),
(144, 0, 'module_social_networks_fb_name', '0', CURRENT_TIMESTAMP),
(145, 0, 'module_social_networks_gplus_user', '0', CURRENT_TIMESTAMP),
(146, 0, 'module_social_networks_gplus_key', '0', CURRENT_TIMESTAMP),
(147, 0, 'module_social_networks_twitter_user', '0', CURRENT_TIMESTAMP),
(148, 5, 'module_cron_job', '0 0 */1 * * php init.php SocialNetworks UpdateFollowers []', CURRENT_TIMESTAMP),
(149, 0, 'module_trigger_type', '["social_networks_update_settings", "Social Networks", "Update settings"]', CURRENT_TIMESTAMP),
(150, 0, 'module_trigger_type', '["social_networks_update_other", "Social Networks", "Update other"]', CURRENT_TIMESTAMP),
(151, 0, 'module_trigger_type', '["social_networks_update_followers", "Social Networks", "Update followers"]', CURRENT_TIMESTAMP),
(152, 0, 'module_blog_db_connection', 'auto', CURRENT_TIMESTAMP),
(153, 0, 'module_blog_posts_on_page', '5', CURRENT_TIMESTAMP),
(154, 0, 'module_trigger_type', '["blog_add_article", "Blog", "Add article"]', CURRENT_TIMESTAMP),
(155, 0, 'module_trigger_type', '["blog_remove_article", "Blog", "Remove article"]', CURRENT_TIMESTAMP),
(156, 0, 'module_trigger_type', '["blog_update_article", "Blog", "Update article"]', CURRENT_TIMESTAMP),
(157, 0, 'module_trigger_type', '["blog_add_articles_group", "Blog", "Add articles group"]', CURRENT_TIMESTAMP),
(158, 0, 'module_trigger_type', '["blog_remove_articles_group", "Blog", "Remove articles group"]', CURRENT_TIMESTAMP),
(159, 0, 'module_trigger_type', '["blog_update_articles_group", "Blog", "Update articles group"]', CURRENT_TIMESTAMP),
(160, 0, 'module_trigger_type', '["blog_update_settings", "Blog", "Update settings"]', CURRENT_TIMESTAMP),
(161, 0, 'module_cache_memcache_host', '127.0.0.1', CURRENT_TIMESTAMP),
(162, 0, 'module_cache_memcache_port', '11211', CURRENT_TIMESTAMP),
(163, 0, 'module_trigger_type', '["update_cache_settings", "Cache", "Update settings"]', CURRENT_TIMESTAMP),
(164, 0, 'module_trigger_type', '["remove_log_file", "Logs", "Remove log file"]', CURRENT_TIMESTAMP),
(165, 0, 'module_sendthis_ssh_connection', '5', CURRENT_TIMESTAMP),
(166, 0, 'module_sendthis_version', '2.0', CURRENT_TIMESTAMP),
(167, 0, 'module_sendthis_key', '0', CURRENT_TIMESTAMP),
(168, 0, 'module_sendthis_tmp_dir', '/tmp', CURRENT_TIMESTAMP),
(169, 5, 'module_cron_job', '*/1 * * * * php init.php SendThis LoadWebhooks []', CURRENT_TIMESTAMP),
(170, 0, 'module_trigger_type', '["sendthis_webhook", "SendThis", "Webhook"]', CURRENT_TIMESTAMP),
(171, 0, 'module_trigger_type', '["sendthis_update_settings", "SendThis", "Update settings"]', CURRENT_TIMESTAMP),
(172, 0, 'module_taskmanager_db_connection', 'auto', CURRENT_TIMESTAMP),
(173, 0, 'module_taskmanager_ssh_connection', '5', CURRENT_TIMESTAMP),
(174, 0, 'module_taskmanager_complete_lifetime', '1 month', CURRENT_TIMESTAMP),
(175, 0, 'module_taskmanager_max_execution_time', '72000', CURRENT_TIMESTAMP),
(176, 0, 'module_taskmanager_memory_limit', '512M', CURRENT_TIMESTAMP),
(177, 0, 'module_taskmanager_tmp_dir', '/tmp', CURRENT_TIMESTAMP),
(178, 5, 'module_cron_job', '*/1 * * * * php init.php TaskManager Exec []', CURRENT_TIMESTAMP),
(179, 5, 'module_cron_job', '*/1 * * * * php init.php TaskManager GC []', CURRENT_TIMESTAMP),
(180, 0, 'module_trigger_type', '["taskmanager_add", "Task Manager", "Add task"]', CURRENT_TIMESTAMP),
(181, 0, 'module_trigger_type', '["taskmanager_update", "Task Manager", "Update task"]', CURRENT_TIMESTAMP),
(182, 0, 'module_trigger_type', '["taskmanager_remove", "Task Manager", "Remove task"]', CURRENT_TIMESTAMP),
(183, 0, 'module_trigger_type', '["taskmanager_exec", "Task Manager", "Exec task"]', CURRENT_TIMESTAMP),
(184, 0, 'module_trigger_type', '["taskmanager_update_settings", "Task Manager", "Update settings"]', CURRENT_TIMESTAMP);

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `touched` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `social_networks_followers` (
  `id` int(11) NOT NULL,
  `network` enum('vk','fb','gplus','twitter') COLLATE utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `task_manager` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `method` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `args` text COLLATE utf8_unicode_ci NOT NULL,
  `state` enum('wait','complete') COLLATE utf8_unicode_ci NOT NULL,
  `cr_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exec_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_visit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `email`, `password`, `role`, `reg_date`, `last_visit`) VALUES
(0, '', '', 'default', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(1, 'admin@phpshell', '', 'admin', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

CREATE TABLE `users_about` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `item` enum('username','mobile_phone','twitter','skype') COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users_accounts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `service` enum('vk','fb','google','ya') CHARACTER SET utf8 NOT NULL,
  `extra` varchar(250) CHARACTER SET utf8 NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `uri` (`uri`);

ALTER TABLE `blog_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_id` (`sub_id`),
  ADD KEY `uri` (`uri`);

ALTER TABLE `comments_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `object_type` (`object_type`),
  ADD KEY `object_type_object_id` (`object_type`,`object_id`) USING BTREE;

ALTER TABLE `comments_objects`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `likes_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `object_type` (`object_type`),
  ADD KEY `object_type_object_id` (`object_type`,`object_id`) USING BTREE;

ALTER TABLE `likes_objects`
  ADD PRIMARY KEY (`id`);

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

ALTER TABLE `registry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`item`),
  ADD KEY `item_sub_id` (`item`,`sub_id`) USING BTREE;

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `social_networks_followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `network` (`network`);

ALTER TABLE `task_manager`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_exec_date` (`state`,`exec_date`) USING BTREE,
  ADD KEY `state_complete_date` (`state`,`complete_date`) USING BTREE;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD KEY `email_password` (`email`,`password`) USING BTREE,
  ADD KEY `role_reg_date` (`role`,`reg_date`) USING BTREE;

ALTER TABLE `users_about`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `user_item` (`user`,`item`) USING BTREE;

ALTER TABLE `users_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service` (`service`);


ALTER TABLE `blog_articles`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `blog_groups`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `comments_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `comments_objects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `likes_list`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `likes_objects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
ALTER TABLE `registry`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;
ALTER TABLE `social_networks_followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `task_manager`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users_about`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users_accounts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `blog_articles`
  ADD CONSTRAINT `blog_articles_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `blog_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `comments_messages`
  ADD CONSTRAINT `comments_messages_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_messages_ibfk_2` FOREIGN KEY (`object_type`) REFERENCES `comments_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `likes_list`
  ADD CONSTRAINT `likes_list_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_list_ibfk_2` FOREIGN KEY (`object_type`) REFERENCES `likes_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_copies`
  ADD CONSTRAINT `mail_copies_ibfk_1` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_events`
  ADD CONSTRAINT `mail_events_ibfk_1` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_events_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_events_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_letters`
  ADD CONSTRAINT `mail_letters_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_letters_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `mail_letters_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_letters_ibfk_3` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_log`
  ADD CONSTRAINT `mail_log_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_log_ibfk_2` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_log_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_log_ibfk_4` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_queue`
  ADD CONSTRAINT `mail_queue_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_queue_ibfk_3` FOREIGN KEY (`letter`) REFERENCES `mail_letters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_queue_ibfk_4` FOREIGN KEY (`sender`) REFERENCES `mail_senders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_queue_ibfk_5` FOREIGN KEY (`transport`) REFERENCES `mail_transport` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mail_queue_ibfk_6` FOREIGN KEY (`log`) REFERENCES `mail_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mail_senders`
  ADD CONSTRAINT `mail_senders_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `mail_senders_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users_about`
  ADD CONSTRAINT `users_about_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users_accounts`
  ADD CONSTRAINT `users_accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
