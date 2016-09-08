<?
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_cron_tmp_file', 
    'module_cron_job'
], PDO::PARAM_STR]]);

APP::Module('Triggers')->Unregister([
    'add_cron_job',
    'update_cron_job',
    'remove_cron_job',
    'update_cron_settings'
]);