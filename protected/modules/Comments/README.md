# Comments
Tree comment system for any objects

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Users](https://github.com/evildevel/php-shell/tree/master/protected/modules/Users)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Comments
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /comments
        ├── /admin
        │   ├── /objects
        │   │   ├── add.php
        │   │   ├── edit.php
        │   │   └── index.php
        │   ├── edit.php
        │   ├── index.php
        │   ├── settings.php
        │   └── nav.php
        └── /widgets
            └── /default
                ├── form.php
                └── list.php
```

### Methods
```php
// Get raw comments
array APP::Module('Comments')->Get(int $type, int $id)
```

### Examples
```php
// Render comments widget
APP::Render('comments/widgets/default/list', 'include', [
    'type' => 4,        // Object type
    'id' => 7,          // Object ID
    'likes' => true,    // Display "Like" button ("Likes" module required)
    'class' => [        // Styles
        'holder' => 'palette-Grey-50 bg p-l-10'
    ]
]);

// Render form widget
APP::Render('comments/widgets/default/form', 'include', [
    'type' => 4,        // Object type
    'id' => 7,          // Object ID
    'login' => [],      // Locked roles e.g. ['default','new']
    'class' => [        // Styles
        'holder' => false,
        'list' => 'palette-Grey-50 bg p-l-10'
    ]
]);
```

### Customization
To change the appearance and behavior of the form and list of comments you must 
create a view in the directory `/protected/render/comments/widgets`. Use the 
path to the new template to render widgets comments.

### Triggers
- Add comment
- Remove comment
- Update comment
- Add comment object
- Remove comment object
- Update comment object
- Update settings

### WEB interfaces
```
/admin/comments                                 // Manage comments
/admin/comments/edit/<message_id_hash>          // Edit comment
/admin/comments/objects                         // Manage comments objects
/admin/comments/objects/add                     // Add comment object
/admin/comments/objects/edit/<object_id_hash>   // Edit comment object
/admin/comments/settings                        // Settings
   
/comments/api/add.json                          // [API] Add comment

/admin/comments/api/list.json                   // [API] List comments
/admin/comments/api/update.json                 // [API] Update comment
/admin/comments/api/remove.json                 // [API] Remove comment
/admin/comments/api/objects/list.json           // [API] List comments objects
/admin/comments/api/objects/add.json            // [API] Add comment object
/admin/comments/api/objects/update.json         // [API] Update comment object
/admin/comments/api/objects/remove.json         // [API] Remove comment object
/admin/comments/api/settings/update.json        // [API] Update settings
```