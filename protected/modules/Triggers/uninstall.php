<?
APP::Module('Registry')->Delete([['item', 'IN', [
    'module_trigger_type', 
    'module_trigger_rule'
], PDO::PARAM_STR]]);

APP::Module('Triggers')->Unregister([
    'add_trigger',
    'remove_trigger',
    'update_trigger'
]);