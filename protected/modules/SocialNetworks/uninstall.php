<?
APP::Module('Cron')->Remove(APP::Module('Mail')->settings['module_social_networks_ssh_connection'], ['0', '0', '*/1', '*', '*', 'php ' . ROOT . '/init.php SocialNetworks UpdateFollowers []']);
APP::Module('DB')->Open(APP::Module('Mail')->settings['module_social_networks_db_connection'])->query('DROP TABLE social_networks_followers');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_social_networks_ssh_connection',
    'module_social_networks_db_connection',
    'module_social_networks_tmp_dir',
    'module_social_networks_vk_gid',
    'module_social_networks_fb_name',
    'module_social_networks_gplus_user',
    'module_social_networks_gplus_key',
    'module_social_networks_twitter_user'
]]]);

APP::Module('Triggers')->Unregister([
    'social_networks_update_settings',
    'social_networks_update_other',
    'social_networks_update_followers'
]);