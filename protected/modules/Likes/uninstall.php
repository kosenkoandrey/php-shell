<?
$likes_db = APP::Module('Likes')->settings['module_likes_db_connection'];

APP::Module('DB')->Open($likes_db)->query('DROP TABLE likes_list');
APP::Module('DB')->Open($likes_db)->query('DROP TABLE likes_objects');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_likes_db_connection'
]]]);

APP::Module('Triggers')->Unregister([
    'likes_add_like',
    'likes_remove_like',
    'likes_add_object',
    'likes_update_object',
    'likes_remove_object',
    'likes_update_settings'
]);