<?
APP::Module('DB')->Open(APP::Module('TaskManager')->settings['module_taskmanager_db_connection'])->query('DROP TABLE task_manager');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_taskmanager_db_connection', 
    'module_taskmanager_complete_lifetime',
    'module_taskmanager_max_execution_time',
    'module_taskmanager_memory_limit',
    'module_taskmanager_tmp_dir'
], PDO::PARAM_STR]]);