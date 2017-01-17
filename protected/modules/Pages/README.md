# Costs
Obtaining information on the costs for Yandex.Direct ad network.

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [Utils](https://github.com/evildevel/php-shell/tree/master/protected/modules/Utils)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Costs
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── test.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /costs
        └── /admin
            ├── /dashboard
            │   ├── css.php
            │   ├── html.php
            │   ├── index.php
            │   └── js.php
            ├── add.php
            ├── edit.php
            ├── index.php
            ├── nav.php
            └── settings.php
            
```

### Triggers
- Download Yandex.Direct costs
- Add manual cost
- Remove cost
- Update cost
- Update costs settings

### WEB interfaces
```
/admin/costs                            Manage costs
/admin/costs/add                        Add cost
/admin/costs/edit/<cost_id_hash>        Edit cost
/admin/costs/settings                   Costs Settings

/admin/costs/yandex/get                 Get Yandex
/admin/costs/yandex/token               Get Yandex token

/admin/costs/api/dashboard.json         [API] Dashboard
/admin/costs/api/search.json            [API] Search costs
/admin/costs/api/action.json            [API] Search action
/admin/costs/api/add.json               [API] Add cost
/admin/costs/api/remove.json            [API] Remove cost
/admin/costs/api/update.json            [API] Update cost
/admin/costs/api/settings/update.json   [API] Update costs settings
```