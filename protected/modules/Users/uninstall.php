<?
$users_db = APP::Module('Users')->settings['module_users_db_connection'];
$mail_db = APP::Module('Mail')->settings['module_mail_db_connection'];

APP::Module('DB')->Open($users_db)->query('DROP TABLE users');
APP::Module('DB')->Open($users_db)->query('DROP TABLE social_accounts');

$group_id = APP::Module('DB')->Select($mail_db, ['fetchColumn', 0], ['id'], 'letters_groups', [['name', '=', 'Users', PDO::PARAM_STR]]);

APP::Module('DB')->Delete($mail_db, 'letters_groups', [['id', '=', $group_id]]);
APP::Module('DB')->Delete($mail_db, 'letters', [['group_id', '=', $group_id]]);

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_users_role', 
    'module_users_rule',
    'module_users_db_connection',
    'module_users_auth_token', 
    'module_users_change_password_service',
    'module_users_check_rules', 
    'module_users_gen_pass_length',
    'module_users_login_service', 
    'module_users_min_pass_length',
    'module_users_register_service', 
    'module_users_reset_password_service',
    'module_users_social_auth_fb_id', 
    'module_users_social_auth_fb_key',
    'module_users_social_auth_google_id', 
    'module_users_social_auth_google_key',
    'module_users_social_auth_vk_id', 
    'module_users_social_auth_vk_key',
    'module_users_social_auth_ya_id', 
    'module_users_social_auth_ya_key',
    'module_users_timeout_activation', 
    'module_users_timeout_email',
    'module_users_timeout_token',
    'module_users_register_activation_letter',
    'module_users_reset_password_letter',
    'module_users_change_password_letter',
    'module_users_register_letter'
]]]);

APP::Module('Triggers')->Unregister([
    'user_logout',
    'user_activate',
    'remove_user',
    'add_user',
    'user_login',
    'user_double_login',
    'register_user',
    'reset_user_password',
    'change_user_password',
    'update_user',
    'add_user_role',
    'remove_user_role',
    'add_user_rule',
    'remove_user_rule',
    'update_user_rule',
    'update_users_oauth_settings',
    'update_users_notifications_settings',
    'update_users_services_settings',
    'update_users_auth_settings',
    'update_users_passwords_settings',
    'update_users_timeouts_settings',
    'update_users_other_settings'
]);