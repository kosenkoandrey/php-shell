<?
APP::Module('Registry')->Delete([['item', '=', 'module_ssh_connection', PDO::PARAM_STR]]);

APP::Module('Triggers')->Unregister([
    'add_ssh_connection',
    'remove_ssh_connection',
    'update_ssh_connection'
]);