<?
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_users_role', 
    'module_users_rule',
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
    'module_users_timeout_token'
]]]);

APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE users');
APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE social_accounts');

$group_id = APP::Module('DB')->Select(APP::Module('Mail')->conf['connection'], ['fetchColumn', 0], ['id'], 'letters_groups', [['name', '=', 'Users', PDO::PARAM_STR]]);

APP::Module('DB')->Delete(APP::Module('Mail')->conf['connection'], 'letters_groups', [['id', '=', $group_id]]);
APP::Module('DB')->Delete(APP::Module('Mail')->conf['connection'], 'letters', [['group_id', '=', $group_id]]);