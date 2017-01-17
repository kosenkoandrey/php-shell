<?
$db = APP::Module('Mail')->settings['module_costs_db_connection'];
$ssh = APP::Module('Mail')->settings['module_costs_ssh_connection'];

APP::Module('Cron')->Remove($ssh, ['1', '1', '*/1', '*', '*', 'php ' . ROOT . '/init.php Costs GetYandex []']);

APP::Module('DB')->Open($db)->query('DROP TABLE costs');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_costs_db_connection',
    'module_costs_ssh_connection',
    'module_costs_tmp_dir',
    'module_costs_max_execution_time',
    'module_costs_yandex_token',
    'module_costs_yandex_client_id',
    'module_costs_yandex_client_secret',
    'module_costs_yandex_utm_source',
    'module_costs_yandex_utm_medium',
    'module_costs_facebook_token',
    'module_costs_facebook_client_id',
    'module_costs_facebook_client_secret'
]]]);

APP::Module('Triggers')->Unregister([
    'download_yandex_costs',
    'add_manual_cost',
    'remove_cost',
    'update_cost',
    'update_costs_settings'
]);