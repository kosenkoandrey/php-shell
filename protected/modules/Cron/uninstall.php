<?
APP::Module('Registry')->Delete([['item', 'IN', ['module_cron_tmp_file', 'module_cron_job'], PDO::PARAM_STR]]);