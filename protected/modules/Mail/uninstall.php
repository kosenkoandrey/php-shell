<?
$db = APP::Module('DB')->Open(APP::Module('Mail')->settings['module_mail_db_connection']);

$db->query('DROP TABLE letters');
$db->query('DROP TABLE letters_groups');
$db->query('DROP TABLE senders');
$db->query('DROP TABLE senders_groups');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_mail_db_connection',
    'module_mail_charset', 
    'module_mail_x_mailer',
    'module_mail_transport'
]]]);

APP::Module('Triggers')->Unregister([
    'mail_add_letter',
    'mail_remove_letter',
    'mail_update_letter',
    'mail_add_letters_group',
    'mail_remove_letters_group',
    'mail_update_letters_group',
    'mail_add_sender',
    'mail_remove_sender',
    'mail_update_sender',
    'mail_add_senders_group',
    'mail_remove_senders_group',
    'mail_update_senders_group',
    'mail_add_transport',
    'mail_remove_transport',
    'mail_update_transport',
    'mail_update_settings',
    'mail_send_letter'
]);