# SendThis
Sending E-Mail via [SendThis](http://sendthis.ru)

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Users](https://github.com/evildevel/php-shell/tree/master/protected/modules/Users)
- [Mail](https://github.com/evildevel/php-shell/tree/master/protected/modules/Mail)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Utils](https://github.com/evildevel/php-shell/tree/master/protected/modules/Utils)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)
- [SSH](https://github.com/evildevel/php-shell/tree/master/protected/modules/SSH)
- [Cron](https://github.com/evildevel/php-shell/tree/master/protected/modules/Cron)

### Files
```
/protected
├── /modules
│   └── /SendThis
│       ├── /daemon
│       │   ├── config.json
│       │   ├── main.c
│       │   └── makefile
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /sendthis
        └── /admin
            ├── index.php
            └── nav.php
```

### Webhooks
To receive email events must specify `http://domain.com/sendthis/api/webhooks.json` 
in SendThis account settings.

### Transport
SendThis module expands ways to transport letters.

| Transport | Description                                                                                                                                                            |
|-----------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Default   | Sending emails with PHP. Instant sending emails in one thread. Suitable for a small number of emails. This method does not require any additional configuration.       |
| Daemon    | Sending emails via daemon using the mail queue. Multithreaded sending packets of letters. Suitable for a large number of emails. It requires additional configuration. |

To set up email using the daemon you need to edit the file `config.json`

| Property  | Description                                                                      |
|-----------|----------------------------------------------------------------------------------|
| threads   | The most productive: 1.5 * [number of cores]                                     |
| delay     | Pause in seconds between requests tasks from the queue                           |
| retries   | Maximum number of attempts to send email and then a letter is marked as rejected |
| pack_size | Number of letters in one pack                                                    |
| api.key   | 64-character key                                                                 |
| db        | Settings of mail queue database connection                                       |

```json
{
	"threads":"4",     
	"delay":"1",        
	"retries":"5",
	"pack_size":"100",
	"api":{
		"url":"http://sendthis.ru/api/2.0/test.php",
		"key":""
	},
	"db":{
		"host":"localhost",
		"database":"phpshell",
		"user":"root",
		"password":""
	}
}
```

### Daemon
To compile the daemon must go to the directory with the file `makefile` and then 
execute the command `make all`. `sendthis20` executable file will be created 
after successful compilation. To start the daemon, run the command 
`sendthis20 config.json`.

### WEB interfaces
```
/admin/sendthis                            // Settings
/admin/sendthis/api/settings/update.json   // [API] Update settings

/sendthis/api/webhooks.json                // [API] Webhooks handler
```

### Credits
Thanks to [Nicolay Belyaev](mailto:bl0ckzer01@gmail.com) for base daemon sources.