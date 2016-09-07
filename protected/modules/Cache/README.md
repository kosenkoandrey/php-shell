# Cache
It is often used to speed up dynamic database-driven websites by caching data and objects in RAM to reduce the number of times an external data source (such as a database or API) must be read.

### Requirements

- [Memcached](http://php.net/manual/en/book.memcached.php)

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)

### Files
```
/protected
├── /modules
│   └── /Cache
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /cache
        └── /admin
            ├── index.php
            └── nav.php
```

### Properties
```php
// Memcached class instance
Memcached Object APP::Module('Cache')->memcache
```

### Examples
```php
// Retrieve an item
APP::Module('Cache')->memcache->get($id);

// Store an item
APP::Module('Cache')->memcache->set($id, $data, $exp);

// Add an item under a new key
APP::Module('Cache')->memcache->add($id, $data, $exp);

// Replace the item under an existing key
APP::Module('Cache')->memcache->replace($id, $data, $exp);

// Delete an item
APP::Module('Cache')->memcache->delete($id);

// Invalidate all items in the cache
APP::Module('Cache')->memcache->flush();
```

### WEB interfaces
```
/admin/cache                            // Cache settings
/admin/cache/api/settings/update.json   // [API] Update cache settings
```