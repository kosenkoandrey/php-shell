# Cron
Manage cron jobs without modifying crontab

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [SSH](https://github.com/evildevel/php-shell/tree/master/protected/modules/SSH)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)

### Files
```
/protected
├── /modules
│   └── /Cron
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /cron
        └── /admin
            ├── add.php
            ├── edit.php
            ├── index.php
            ├── jobs.php
            ├── nav.php
            └── settings.php
```

### Methods
```php
// Add cron job
mixed APP::Module('Cron')->Add(int $ssh, array $job)

// Remove cron job
mixed APP::Module('Cron')->Remove(int $ssh, array $job)
```

### Triggers
- Add cron job
- Update cron job
- Remove cron job
- Update cron settings

### WEB interfaces
```
/admin/cron                             // Select SSH connection
/admin/cron/settings                    // Settings   
/admin/cron/jobs/edit/<job_id_hash>     // Edit job
/admin/cron/jobs/<ssh_id_hash>/add      // Add job
/admin/cron/jobs/<ssh_id_hash>          // Manage jobs

/admin/cron/api/jobs/list.json          // [API] List jobs
/admin/cron/api/jobs/add.json           // [API] Add job
/admin/cron/api/jobs/update.json        // [API] Update job
/admin/cron/api/jobs/remove.json        // [API] Remove job
/admin/cron/api/settings/update.json    // [API] Update settings
```

### Examples
```php
// Add cron job
APP::Module('Cron')->Add(1, ['*/1', '*', '*', '*', '*', 'php /cron_1min.php >/dev/null 2>&1']);

// Remove cron job
APP::Module('Cron')->Remove(1, ['*/1', '*', '*', '*', '*', 'php /cron_1min.php >/dev/null 2>&1']);
```