# Logs
Manage log files

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Utils](https://github.com/evildevel/php-shell/tree/master/protected/modules/Utils)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Logs
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /logs
        └── /admin
            ├── index.php
            ├── nav.php
            └── view.php
```

### Triggers
- Remove log file

### WEB interfaces
```
/admin/logs                             // Manage logs
/admin/logs/view/<filename_hash>        // View log
        
/admin/logs/api/list.json               // [API] List logs
/admin/logs/api/remove.json             // [API] Remove log
```