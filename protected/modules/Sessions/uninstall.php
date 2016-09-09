<?
APP::Module('DB')->Open(APP::Module('Sessions')->settings['module_sessions_db_connection'])->query('DROP TABLE sessions');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_sessions_db_connection',
    'module_sessions_cookie_domain', 
    'module_sessions_cookie_lifetime',
    'module_sessions_compress', 
    'module_sessions_gc_maxlifetime'
]]]);

APP::Module('Triggers')->Unregister('update_sessions_settings');