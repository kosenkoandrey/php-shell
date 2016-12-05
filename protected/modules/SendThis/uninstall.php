<?
$ssh = APP::Module('SendThis')->settings['module_sendthis_ssh_connection'];

APP::Module('Cron')->Remove($ssh, ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php SendThis LoadWebhooks []']);

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_sendthis_ssh_connection',
    'module_sendthis_version',
    'module_sendthis_key',
    'module_sendthis_tmp_dir'
], PDO::PARAM_STR]]);

APP::Module('DB')->Delete(APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_transport', [['module', '=', 'SendThis', PDO::PARAM_STR]]);

APP::Module('Triggers')->Unregister([
    'sendthis_webhook',
    'sendthis_update_settings'
]);