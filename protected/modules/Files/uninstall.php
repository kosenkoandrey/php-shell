<?
$db = APP::Module('Files')->settings['module_files_db_connection'];

APP::Module('DB')->Open($db)->query('DROP TABLE files');
APP::Module('DB')->Open($db)->query('DROP TABLE files_groups');
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_files_db_connection',
    'module_files_mime',
    'module_files_path'
]]]);

APP::Module('Triggers')->Unregister([
    'files_add',
    'files_remove',
    'files_update',
    'files_add_group',
    'files_remove_group',
    'files_update_group'
]);