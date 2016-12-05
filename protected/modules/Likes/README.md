# Likes
Like any objects

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Users](https://github.com/evildevel/php-shell/tree/master/protected/modules/Users)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Likes
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /likes
        ├── /admin
        │   ├── /objects
        │   │   ├── add.php
        │   │   ├── edit.php
        │   │   └── index.php
        │   ├── index.php
        │   ├── settings.php
        │   └── nav.php
        └── /widgets
            └── default.php
```

### Methods
```php
// Get likes of object 
array APP::Module('Likes')->Get(int $type, int $id[, int $users = 6])
```

### Examples
```php
APP::Render('likes/widgets/default', 'include', [
    'type' => 4,        // Object type
    'id' => 7,          // Object ID
    'text' => 'Like',   // Button text
    'class' => [],      // Button class e.g. ['btn-xs', 'f-700']
    'details' => false  // Display users list
]);
```

### Customization
To change the appearance and behavior of the like buttons you must 
create a view in the directory `/protected/render/likes/widgets`. Use the path 
to the new template to render like widgets.

### Triggers
- Add like
- Remove like
- Add like object
- Remove like object
- Update like object
- Update settings

### WEB interfaces
```
/admin/likes                                 // Manage likes
/admin/likes/objects                         // Manage likes objects
/admin/likes/objects/add                     // Add like object
/admin/likes/objects/edit/<object_id_hash>   // Edit like object
/admin/likes/settings                        // Settings
   
/likes/api/toggle.json                       // [API] Toggle like
/likes/api/users.json                        // [API] List users

/admin/likes/api/list.json                   // [API] List likes
/admin/likes/api/remove.json                 // [API] Remove like
/admin/likes/api/objects/list.json           // [API] List likes objects
/admin/likes/api/objects/add.json            // [API] Add like object
/admin/likes/api/objects/update.json         // [API] Update like object
/admin/likes/api/objects/remove.json         // [API] Remove like object
/admin/likes/api/settings/update.json        // [API] Update settings
```