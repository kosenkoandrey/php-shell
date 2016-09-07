<?
APP::Module('Registry')->Delete([['item', 'IN', ['module_cache_memcache_host', 'module_cache_memcache_port'], PDO::PARAM_STR]]);