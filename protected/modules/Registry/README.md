# Registry
Simple data store

### Dependencies
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)

### Files
```
/protected
└── /modules
    └── /Registry
        ├── MANIFEST
        ├── README.md
        ├── class.php
        ├── conf.php
        ├── install.php
        └── uninstall.php
```

### Methods
```php
// Get registry data
mixed APP::Module('Registry')->Get(mixed $item[, mixed $fields = 'value'[, mixed $sub_id = 0]])

// Add registry data
int APP::Module('Registry')->Add(string $item, string $value[, int $sub_id = 0])

// Delete registry data
int APP::Module('Registry')->Delete(array $where)

// Update registry data
int APP::Module('Registry')->Update(array $fields, array $where)
```

### Examples
```php
// Get
APP::Module('Registry')->Get('foo');
APP::Module('Registry')->Get(['foo', 'bar']);
APP::Module('Registry')->Get(['foo'], 'bar');
APP::Module('Registry')->Get(['foo'], ['bar', 'baz']);
APP::Module('Registry')->Get(['foo'], ['bar'], 'baz');
APP::Module('Registry')->Get(['foo'], ['bar'], ['baz', 'quux']);

// Add
APP::Module('Registry')->Add('foo', 'bar');
APP::Module('Registry')->Add('foo', 'bar', 'baz');

// Delete
APP::Module('Registry')->Delete([['foo', '=', 'bar', PDO::PARAM_STR]]);
APP::Module('Registry')->Delete([
    ['foo', '=', 'bar', PDO::PARAM_STR],
    ['baz', '!=', 'quux', PDO::PARAM_INT]
]);

// Update
APP::Module('Registry')->Update(['foo' => 'bar'], [['baz', '=', 'quux', PDO::PARAM_STR]]);
```