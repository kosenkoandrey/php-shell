# SocialNetworks
Getting information about followers in social networks

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)
- [Cron](https://github.com/evildevel/php-shell/tree/master/protected/modules/Cron)

### Files
```
/protected
├── /modules
│   └── /SocialNetworks
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /social_networks
        ├── /admin
        │   ├── index.php
        │   ├── nav.php
        │   ├── other.php
        │   └── settings.php
        └── /widgets
            └── default.php
```

### Methods
```php
// Get followers 
array APP::Module('SocialNetworks')->GetFollowers([$fields = ['count'][, $last = true]])
```

### Examples
```php
APP::Render('social_networks/widgets/default')
```

### Customization
To change the template you must create a view in the directory 
`/protected/render/social_networks/widgets`. Use the path to the new template to 
render template widgets.

### Triggers
- Update pages settings
- Update other settings
- Update followers

### WEB interfaces
```
/admin/social_networks                             // Overview
/admin/social_networks/settings                    // Social networks settings
/admin/social_networks/other                       // Other social networks settings

/admin/social_networks/api/settings/update.json    // [API] Update social networks settings
/admin/social_networks/api/other/update.json       // [API] Update other social networks settings
```