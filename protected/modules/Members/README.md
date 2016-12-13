# Members
Members system

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
│   └── /Members
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── test.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /members
        └── /admin
            ├── /page
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

### Triggers
- Add page
- Remove page
- Update page
- Add group of pages
- Remove group of pages
- Update group of pages


### WEB interfaces
```
admin/members/pages/<group_sub_id_hash>/preview/<page_id_hash>           // Preview page
admin/members/pages/<group_sub_id_hash>/groups/add                       // Add pages group
admin/members/pages/<group_sub_id_hash>/groups/<group_id_hash>/edit      // Edit pages group
admin/members/pages/<group_sub_id_hash>/add                              // Add page
admin/members/pages/<group_sub_id_hash>/edit/<page_id_hash>              // Edit page
admin/members/pages/<group_sub_id_hash>                                  // Manage pages

admin/members/settings                                                   // Members settings

// API

admin/members/api/pages/add.json                          // [API] Add page
admin/members/api/pages/remove.json                       // [API] Remove page
admin/members/api/pages/update.json                       // [API] Update page
admin/members/api/pages/groups/add.json                   // [API] Add pages group
admin/members/api/pages/groups/remove.json                // [API] Remove pages group
admin/members/api/pages/groups/update.json                // [API] Update pages group
admin/members/api/pages/get.json                          // [API] Get page

admin/members/api/settings/update.json                    // [API] Update members settings
```