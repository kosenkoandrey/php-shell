# Admin
Tools for managing system components

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Utils](https://github.com/evildevel/php-shell/tree/master/protected/modules/Utils)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Users](https://github.com/evildevel/php-shell/tree/master/protected/modules/Users)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Admin
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /admin
        ├── /modules
        │   ├── import.php
        │   ├── network_import.php
        │   ├── uninstall.php
        │   └── install.php
        ├── /widgets
        │   ├── footer.php
        │   ├── header.php
        │   └── sidebar.php
        └── index.php
```

### Triggers
- Import locale module
- Remove imported module
- Export module
- Uninstall module

### WEB interfaces
```
/admin                                          // Manage
/admin/modules/export/<module_hash>             // Export module
/admin/modules/import                           // Import modules
/admin/modules/import/network                   // Network import modules
/admin/modules/import/remove/<module_path>      // Remove imported module
/admin/modules/import/install                   // Install imported modules
/admin/modules/uninstall/<module_hash>          // Uninstall module
        
/admin/api/modules/uninstall/<module_hash>      // [API] Uninstall module
```