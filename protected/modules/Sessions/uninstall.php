<?
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_sessions_cookie_domain', 
    'module_sessions_cookie_lifetime',
    'module_sessions_compress', 
    'module_sessions_gc_maxlifetime'
]]]);

APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE sessions');