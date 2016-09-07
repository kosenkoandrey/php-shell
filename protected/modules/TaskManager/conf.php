<?
return [
    'routes' => [
        ['admin\/taskmanager(\?.*)?',                               'TaskManager', 'TaskManage'],           // Manage tasks
        ['admin\/taskmanager\/add',                                 'TaskManager', 'AddTask'],              // Add task
        ['admin\/taskmanager\/edit\/(?P<task_id_hash>.*)',          'TaskManager', 'EditTask'],             // Edit task
        ['admin\/taskmanager\/settings(\?.*)?',                     'TaskManager', 'Settings'],             // Settings
        
        ['admin\/taskmanager\/api\/list\.json(\?.*)?',              'TaskManager', 'APITaskList'],          // [API] List tasks
        ['admin\/taskmanager\/api\/add\.json(\?.*)?',               'TaskManager', 'APIAddTask'],           // [API] Add task
        ['admin\/taskmanager\/api\/update\.json(\?.*)?',            'TaskManager', 'APIUpdateTask'],        // [API] Update task
        ['admin\/taskmanager\/api\/remove\.json(\?.*)?',            'TaskManager', 'APIRemoveTask'],        // [API] Remove task
        ['admin\/taskmanager\/api\/settings\/update\.json(\?.*)?',  'TaskManager', 'APIUpdateSettings'],    // [API] Update settings
    ]
];