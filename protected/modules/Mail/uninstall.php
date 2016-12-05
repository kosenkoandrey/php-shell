<?
$db = APP::Module('Mail')->settings['module_mail_db_connection'];
$ssh = APP::Module('Mail')->settings['module_mail_ssh_connection'];

APP::Module('Cron')->Remove($ssh, ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php Mail CopiesGC []']);

APP::Module('DB')->Open($db)->query('DROP TABLE mail_copies');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_events');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_letters');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_letters_groups');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_log');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_queue');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_senders');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_senders_groups');
APP::Module('DB')->Open($db)->query('DROP TABLE mail_transport');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_mail_db_connection',
    'module_mail_ssh_connection', 
    'module_mail_tmp_dir', 
    'module_mail_charset', 
    'module_mail_x_mailer',
    'module_mail_save_sent_email',
    'module_mail_sent_email_lifetime'
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
    'mail_remove_log_entry',
    'mail_remove_queue_entry',
    'before_mail_send_letter',
    'after_mail_send_letter',
    'mail_event_processed',
    'mail_event_delivered',
    'mail_event_deferred',
    'mail_event_bounce_hard',
    'mail_event_bounce_soft',
    'mail_event_unsubscribe',
    'mail_event_spamreport',
    'mail_event_open',
    'mail_event_click'
]);