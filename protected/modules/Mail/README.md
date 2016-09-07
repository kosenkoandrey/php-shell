# Mail
Simple E-Mail sending. Senders and letters management system.

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
│   └── /Mail
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /mail
        └── /admin
            ├── /letters
            │   ├── /groups
            │   │   ├── add.php
            │   │   └── edit.php
            │   ├── add.php
            │   ├── edit.php
            │   ├── index.php
            │   └── preview.php
            ├── /senders
            │   ├── /groups
            │   │   ├── add.php
            │   │   └── edit.php
            │   ├── add.php
            │   ├── edit.php
            │   └── index.php
            ├── nav.php
            └── settings.php
```

### Methods
```php
// Send E-Mail message
array APP::Module('Mail')->Send(array $from, string $to, string $subject, array $message[, array $headers = false])
```

### Examples
```php
APP::Module('Mail')->Send(
    Array(
        'from email', 
        'from name'
    ), 
    'to email', 
    'subject', 
    Array(
        'html message',
        'plaintext message'
    ),
    // headers (optional)
    Array(
        'List-id' => 'php-shell'
    )
);
```

### Triggers
- Add letter
- Remove letter
- Update letter
- Add group of letters
- Remove group of letters
- Update group of letters
- Add sender
- Remove sender
- Update sender
- Add group of senders
- Remove group of senders
- Update group of senders
- Update mail settings
- Send mail

### WEB interfaces
```
/admin/mail/letters/<group_sub_id_hash>/preview/<letter_id_hash>         // Preview letter
/admin/mail/letters/<group_sub_id_hash>/groups/add                       // Add letters group
/admin/mail/letters/<group_sub_id_hash>/groups/<group_id_hash>/edit      // Edit letters group
/admin/mail/letters/<group_sub_id_hash>/add                              // Add letter
/admin/mail/letters/<group_sub_id_hash>/edit/<letter_id_hash>            // Edit letter
/admin/mail/letters/<group_sub_id_hash>                                  // Manage letters
/admin/mail/senders/<group_sub_id_hash>/groups/add                       // Add senders group
/admin/mail/senders/<group_sub_id_hash>/groups/<group_id_hash>/edit      // Edit senders group
/admin/mail/senders/<group_sub_id_hash>/add                              // Add sender
/admin/mail/senders/<group_sub_id_hash>/edit/<sender_id_hash>            // Edit sender
/admin/mail/senders/<group_sub_id_hash>                                  // Manage senders
/admin/mail/settings                                                     // Mail settings

/admin/mail/api/letters/add.json                                         // [API] Add letter
/admin/mail/api/letters/remove.json                                      // [API] Remove letter
/admin/mail/api/letters/update.json                                      // [API] Update letter
/admin/mail/api/letters/groups/add.json                                  // [API] Add letters group
/admin/mail/api/letters/groups/remove.json                               // [API] Remove letters group
/admin/mail/api/letters/groups/update.json                               // [API] Update letters group
/admin/mail/api/senders/add.json                                         // [API] Add sender
/admin/mail/api/senders/remove.json                                      // [API] Remove sender
/admin/mail/api/senders/update.json                                      // [API] Update sender
/admin/mail/api/senders/groups/add.json                                  // [API] Add senders group
/admin/mail/api/senders/groups/remove.json                               // [API] Remove senders group
/admin/mail/api/senders/groups/update.json                               // [API] Update senders group
/admin/mail/api/settings/update.json                                     // [API] Update mail settings
```