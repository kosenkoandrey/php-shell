<?
$db = APP::Module('Members')->settings['module_members_db_connection'];

APP::Module('DB')->Open($db)->query('DROP TABLE members_pages');
APP::Module('DB')->Open($db)->query('DROP TABLE members_pages_groups');
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_members_db_connection'
]]]);

APP::Module('Triggers')->Unregister([
    'members_add_page',
    'members_remove_page',
    'members_update_page',
    'members_add_pages_group',
    'members_remove_pages_group',
    'members_update_pages_group'
]);