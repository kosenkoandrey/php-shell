# Utils

### Dependencies
NONE

### Files
```
/protected
└── /modules
    └── /Utils
        ├── MANIFEST
        ├── README.md
        ├── class.php
        ├── install.php
        └── uninstall.php
```

### Methods
```php
// Convert file size into human readable file size
string APP::Module('Utils')->SizeConvert(int $bytes)

// Check is assoc array
string APP::Module('Utils')->IsAssocArray(array $array)
```