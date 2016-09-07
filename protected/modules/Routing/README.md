# Routing
Fast and powerful router that maps route callbacks to specific HTTP request 
methods and URIs. It supports parameters and pattern matching.

### Dependencies
NONE

### Files
```
/protected
├── /modules
│   └── /Routing
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       └── uninstall.php
└── /render
    └── /routing
        └── errors.php
```

### Properties
```php
// Root application URL
string APP::Module('Routing')->root

// Detailed information about the request
array APP::Module('Routing')->request

// An array of variables passed to script via the URL parameters
array APP::Module('Routing')->get
```

### Methods
```php
// Add route
void APP::Module('Routing')->Add(string $rule, string $module, string $method[, mixed $data = null])

// Get request URL
string APP::Module('Routing')->SelfUrl()

// Get request URI
string APP::Module('Routing')->RequestURI()
```

### Examples
```php
APP::Module('Routing')->Add('hello\/(?P<name>[a-zA-Z0-9\-\_]+)(\?.*)?', 'Module', 'Method');
APP::Module('Routing')->Add('test', 'Module', 'Method', Array('foo' => 'bar'));
```