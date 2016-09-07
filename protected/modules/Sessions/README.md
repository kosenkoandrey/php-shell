# Sessions
Storing Sessions in a Database

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Sessions
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /sessions
        └── /admin
            ├── index.php
            └── nav.php
```

### Properties
```php
// An array containing session variables
mixed APP::Module('Sessions')->session
```

### Methods
```php
// Read session data
mixed APP::Module('Sessions')->Read(string $id)

// Write session data
bool APP::Module('Sessions')->Write(string $id, string $data)

// Destroy session
bool APP::Module('Sessions')->Destroy(string $id)

// Serialize session data
string APP::Module('Sessions')->Serialize(mixed $data[, bool $safe = true])

// Unserialize session data
mixed APP::Module('Sessions')->Unserialize(string $data)
```

### Triggers
- Update sessions settings

### WEB interfaces
```
/admin/sessions                              // Sessions settings
/admin/sessions/api/settings/update.json     // [API] Update sessions settings
```