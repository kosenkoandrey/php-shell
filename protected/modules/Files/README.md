# Files
Files system.

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Files
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── test.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /files
        └── /admin
            ├── /file
            │   ├── /groups
            │   │   ├── add.php
            │   │   └── edit.php
            │   ├── add.php
            │   ├── edit.php
            │   ├── index.php
            │   └── preview.php
            ├── nav.php
            └── settings.php
```

### Methods
```php
// Upload File
array APP::Module('Files')->FileUpload(array $file, string $path)
```

### Examples
```php
APP::Module('Files')->FileUpload(
    $_FILES,                    // file 
    '/var/www/tmp/10.jpg',      // path
);
```

### Triggers
- Add file
- Remove file
- Update file
- Add group of file
- Remove group of file
- Update group of file


### WEB interfaces
```
admin/files/file/<group_sub_id_hash>/preview/<file_id_hash>             // Preview file
admin/files/file/<group_sub_id_hash>/groups/add                         // Add file group
admin/files/file/<group_sub_id_hash>/groups/<group_id_hash>/edit        // Edit file group
admin/files/file/<group_sub_id_hash>/add                                // Add file
admin/files/file/<group_sub_id_hash>/edit/<file_id_hash>                // Edit file
admin/files/file/<group_sub_id_hash>                                    // Manage file
admin/files/settings                                                    // Files settings

// API

admin/files/api/file/add.json                   // [API] Add file
admin/files/api/file/remove.json                // [API] Remove file
admin/files/api/file/update.json                // [API] Update file
admin/files/api/file/groups/add.json            // [API] Add file group
admin/files/api/file/groups/remove.json         // [API] Remove file group
admin/files/api/file/groups/update.json         // [API] Update file group
admin/files/api/file/get.json                   // [API] Get file

admin/files/api/settings/update.json            // [API] Update files settings
```