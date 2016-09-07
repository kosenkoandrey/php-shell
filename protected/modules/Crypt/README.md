# Crypt
Tools for data encryption

### Requirements

- [Mcrypt](http://php.net/manual/en/book.mcrypt.php)

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)

### Files
```
/protected
├── /modules
│   └── /Crypt
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /crypt
        └── /admin
            ├── index.php
            └── nav.php
```

### Methods
```php
// Encrypt plaintext (Rijndael 256-bit ECB)
string APP::Module('Crypt')->Encode(string $text)

// Decrypt plaintext (Rijndael 256-bit ECB)
string APP::Module('Crypt')->Decode(string $text)

// Safe encode plaintext (base64)
string APP::Module('Crypt')->SafeB64Encode(string $text)

// Safe decode plaintext (base64)
string APP::Module('Crypt')->SafeB64Decode(string $text)
```

### WEB interfaces
```
/admin/crypt                            // Crypt settings
/admin/crypt/api/settings/update.json   // [API] Update crypt settings
```