# Mail
Powerful E-Mail sending. Senders and letters management system.

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)
- [SSH](https://github.com/evildevel/php-shell/tree/master/protected/modules/SSH)
- [Cron](https://github.com/evildevel/php-shell/tree/master/protected/modules/Cron)

### Files
```
/protected
├── /modules
│   └── /Mail
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── test.php
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
            ├── /transport
            │   ├── add.php
            │   ├── edit.php
            │   └── index.php
            ├── copy.php
            ├── log.php
            ├── queue.php
            ├── nav.php
            └── settings.php
```

### Methods
```php
// Send E-Mail message
array APP::Module('Mail')->Send(string $recepient, int $letter[, mixed $params = false])
```

### Examples
```php
APP::Module('Mail')->Send(
    'user@domain.com',  // Recepient E-Mail
    1,                  // Letter ID
    ['name' => 'Max']   // Available in a letter like $data['name']
);
```

### Transport
The default transport is performed by `Mail / Transport`. Transport method 
configured for each letter. Any module can add a new way of letters of transport 
`INSERT INTO mail_transport VALUES (NULL, "Module", "Method", "Settings URI", NOW())`. 
The list of available transport methods on page `/admin/mail/transport`.

### Webhooks
Saving information about events and sent letters only to registered recipients.

### Containers
You can use containers in the subject, html/text versions of emails for 
automatic substitution of values.

| Container         | Description                                            |
|-------------------|--------------------------------------------------------|
| [user_email]      | Recepient E-Mail address                               |
| [user_id]         | Recepient ID ("Users" module must be installed)        |
| [letter_hash]     | Encoded Mail Log ID ("Users" module must be installed) |
| [encode][/encode] | Encode content with Crypt/Encode module                |
| [decode][/decode] | Decode content with Crypt/Encode module                |

This is a basic set of containers. A set of containers may be expanded using 
other transport messages.

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
- Remove log entry
- Remove queue entry
- Send mail (before)
- Send mail (after)
- Add transport
- Remove transport
- Update transport
- Processed event
- Delivered event
- Deferred event
- Bounce (hard) event
- Bounce (soft) event
- Unsubscribe event
- Spamreport event
- Open event
- Click event

### WEB interfaces
```
/mail/<version>/<letter_id_hash>                                         // View copies

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
/admin/mail/transport/add                                                // Add transport
/admin/mail/transport/edit/<transport_id_hash_id_hash>                   // Edit transport
/admin/mail/transport                                                    // Manage transport
/admin/mail/settings                                                     // Mail settings
/admin/mail/log                                                          // Manage log
/admin/mail/queue                                                        // Manage queue

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
/admin/mail/api/transport/add.json                                       // [API] Add transport
/admin/mail/api/transport/remove.json                                    // [API] Remove transport
/admin/mail/api/transport/update.json                                    // [API] Update transport
/admin/mail/api/settings/update.json                                     // [API] Update mail settings
/admin/mail/api/log/list.json                                            // [API] List log
/admin/mail/api/log/remove.json                                          // [API] Remove log entry
/admin/mail/api/queue/list.json                                          // [API] List queue
/admin/mail/api/queue/remove.json                                        // [API] Remove queue entry
/admin/mail/api/events/list.json                                         // [API] List events
```