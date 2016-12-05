<?
$comments_db = APP::Module('Comments')->settings['module_comments_db_connection'];

APP::Module('DB')->Open($comments_db)->query('DROP TABLE comments_messages');
APP::Module('DB')->Open($comments_db)->query('DROP TABLE comments_objects');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_comments_db_connection'
]]]);

APP::Module('Triggers')->Unregister([
    'comments_add_message',
    'comments_update_message',
    'comments_remove_message',
    'comments_add_object',
    'comments_update_object',
    'comments_remove_object',
    'comments_update_settings'
]);