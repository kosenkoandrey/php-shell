# SSH
Access to resources (shell, remote exec, tunneling, file transfer) on a remote machine using a secure cryptographic transport

### Requirements

- [Secure Shell2](http://php.net/manual/ru/book.ssh2.php)

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /SSH
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /ssh
        └── /admin
            ├── add.php
            ├── edit.php
            ├── index.php
            └── nav.php
```

### Methods
```php
// Get connection to a remote SSH server
resource APP::Module('SSH')->Open(int $id)

// Execute a command on a remote server
resource APP::Module('SSH')->Exec(int $con, string $cmd)
```

### Triggers
- Add connection
- Remove connection
- Update connection

### WEB interfaces
```
/admin/ssh                              // Manage connections
/admin/ssh/add                          // Add connection
/admin/ssh/edit/<connection_id_hash>    // Edit connection
   
/admin/ssh/api/list.json                // [API] List connections     
/admin/ssh/api/add.json                 // [API] Add connection
/admin/ssh/api/update.json              // [API] Update connection
/admin/ssh/api/remove.json              // [API] Remove connection
```

### Examples
```php
// Get connection to a remote SSH server
APP::Module('SSH')->Open(1);

// Execute a command on a remote server
APP::Module('SSH')->Exec(1, 'php /example.php');
```