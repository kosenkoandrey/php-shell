<?
$ssh_connections = APP::Module('Registry')->Get(['module_ssh_connection'], ['id']);

foreach (array_key_exists('module_ssh_connection', $ssh_connections) ? (array) $ssh_connections['module_ssh_connection'] : [] as $ssh_connection) {
    APP::Module('Cron')->Remove($ssh_connection['id'], ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php TaskManager Exec']);
    APP::Module('Cron')->Remove($ssh_connection['id'], ['*/1', '*', '*', '*', '*', 'php ' . ROOT . '/init.php TaskManager GC']);
}

APP::Module('DB')->Open(APP::Module('TaskManager')->settings['module_taskmanager_db_connection'])->query('DROP TABLE task_manager');

APP::Module('Registry')->Delete([['item', 'IN', [
    'module_taskmanager_db_connection', 
    'module_taskmanager_complete_lifetime',
    'module_taskmanager_max_execution_time',
    'module_taskmanager_memory_limit',
    'module_taskmanager_tmp_dir'
], PDO::PARAM_STR]]);

APP::Module('Triggers')->Unregister([
    'taskmanager_add',
    'taskmanager_update',
    'taskmanager_remove',
    'taskmanager_exec',
    'taskmanager_update_settings'
]);