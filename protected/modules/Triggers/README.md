# Triggers
The triggers allow you to configure actions to be automatically performed on some events

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)

### Files
```
/protected
├── /modules
│   └── /Triggers
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /triggers
        └── /admin
            ├── add.php
            ├── edit.php
            ├── index.php
            └── nav.php
```

### Methods
```php
// Register trigger type
mixed APP::Module('Triggers')->Register(str $id, str $group, str $name)

// Unregister trigger type
mixed APP::Module('Triggers')->Unregister(str $id)

// Execute some trigger
mixed APP::Module('Triggers')->Exec(str $id, mixed $data)
```

### Triggers
- Add trigger
- Remove trigger
- Update trigger

### WEB interfaces
```
/admin/triggers                             // Manage triggers
/admin/triggers/add                         // Add trigger
/admin/triggers/edit/<trigger_id_hash>      // Edit trigger
   
/admin/triggers/api/list.json               // [API] List triggers     
/admin/triggers/api/add.json                // [API] Add trigger
/admin/triggers/api/update.json             // [API] Update trigger
/admin/triggers/api/remove.json             // [API] Remove trigger
```

### Examples
```php
// Register new trigger type (usually used in the installer modules)
APP::Module('Triggers')->Register(
    'add_ssh_connection',   // id
    'SSH',                  // group
    'Add connection'        // name
);

/*
Assign module method to trigger

Trigger: SSH / Add connection
Module: TriggerHandler
Method: AddSSHConnection
*/

// Execute trigger on event "add SSH connection" (in the class module)
$connection_id = APP::Module('Registry')->Add(
    'module_ssh_connection', 
    json_encode([
        $_POST['host'], 
        $_POST['port'], 
        $_POST['user'], 
        APP::Module('Crypt')->Encode($_POST['password'])
    ])
);

APP::Module('Triggers')->Exec('add_ssh_connection', [
    'id' => $connection_id,
    'host' => $_POST['host'],
    'port' => $_POST['port'],
    'user' => $_POST['user'],
    'password' => $_POST['password']
]);

// Trigger handler
class TriggerHandler {
    public function AddSSHConnection($id, $data) {
        echo $id; // add_ssh_connection

        print_r($data);
        /*
        Array 
        (
            [id] => 123
            [host] => 127.0.0.1
            [port] => 22
            [user] => evildevel
            [password] => ********
        )
        */
    }
}

// Unregister trigger type (usually used in the uninstall script of module)
APP::Module('Triggers')->Unregister('add_ssh_connection');
```