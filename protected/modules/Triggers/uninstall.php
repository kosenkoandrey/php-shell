<?
APP::Module('Registry')->Delete([['item', 'IN', ['module_trigger_type', 'module_trigger_rule'], PDO::PARAM_STR]]);