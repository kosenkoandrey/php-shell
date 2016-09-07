# TaskManager
Execution methods of models with options on a schedule

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Cron](https://github.com/evildevel/php-shell/tree/master/protected/modules/Cron)

### Files
```
/protected
├── /modules
│   └── /TaskManager
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /taskmanager
        └── /admin
            ├── add.php
            ├── edit.php
            ├── index.php
            ├── settings.php
            └── nav.php
```

### Properties
```php
// Settings
array APP::Module('TaskManager')->settings
```

### Methods
```php
// Add task
int APP::Module('TaskManager')->Add(str $module, str $method, str $exec_date[, str $args = '[]'[, str $token = false[, str $state = 'wait']]])
```

### Triggers
- Add task
- Update task
- Remove task
- Exec task
- Update settings

### WEB interfaces
```
/admin/taskmanager                              // Manage tasks
/admin/taskmanager/add                          // Add task
/admin/taskmanager/edit/<task_id_hash>          // Edit task
/admin/taskmanager/settings                     // Settings
   
/admin/taskmanager/api/list.json                // [API] List tasks     
/admin/taskmanager/api/add.json                 // [API] Add task
/admin/taskmanager/api/update.json              // [API] Update task
/admin/taskmanager/api/remove.json              // [API] Remove task
/admin/taskmanager/api/settings/update.json     // [API] Update settings
```

### Examples
```php
// Run method "Send" of module "Mail" on 2017-01-25 19:30:00
APP::Module('TaskManager')->Add(
    'Mail', 
    'Send', 
    '2017-01-25 19:30:00', 
    '[["from email","from name"],"to email","subject",["html message","plaintext message"],{"List-id":"php-shell"}]', 
    'send_test_email', 
    'wait'
);
```