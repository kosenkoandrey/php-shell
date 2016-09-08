![travis](https://travis-ci.org/evildevel/php-shell.svg?branch=master)

# php-shell
Micro PHP Framework

### Requirements

You need at least `PHP 5.6` with extensions:

- [Zip](http://php.net/manual/en/book.zip.php)

### Install

- [Download](https://php-shell.com/public/export/core/php-shell.zip) the latest version
- Upload and unzip the archive to your server
- Set `install = true` in file `conf.php` to activate the module installation
- Set `location` in file `conf.php` to according your domain
- Follow `/import` to import modules via network

Note: You can manually [download](https://php-shell.com/downloads) and copy 
modules into the directory `/protected/import` on your server. Follow `/install` 
to start installation of imported modules. Best suited if the server cannot 
connect to the Internet.

### Configuration
```php
array APP::$conf  // Core config
```

### Modules
```php
object APP::Module(string $classname)                           // Instance of a module class
mixed APP::Module(string $classname)->conf                      // Config of a module

object APP::LoadModule(string $path)                            // Load module
mixed APP::InitModule(object $module [, bool $force = false])   // Init module
```

### Render
Root directory: `/protected/render/`

Default file extension: `.php`
```php
mixed APP::Render(string $src[, string $mode = 'include'[, mixed $data = null[, string $ext = '.php']]])

// Mode
APP::Render('src')              // Include file
APP::Render('src', 'include')   // Same "include src"
APP::Render('src', 'content')   // Return the contents of the output buffer
APP::Render('src', 'return')    // Same "return include src"
APP::Render('src', 'eval')      // Same "return eval($src)"

// Data transfer
APP::Render('src', 'include', ['foo' => 'bar'])

// Return the contents of the src.csv
APP::Render('src', 'content', null, '.csv')

// Return eval content
APP::Render('<?= echo 'data' ?>', 'eval')
```

### Errors
To configure the error logging must be enabled `APP::$conf['logs']`. Set full 
path to logs directory or `false` to disable logging. Set write permission for 
the logs directory. In the log directory are created files `php-errors-[d-m-Y].log`. 

Each call 
```php
throw new Exception('message');
```
will be added record to the log file.

### Console
```php
// Example module
class Console {
    public function Test($arg1, $arg2, $arg3) {}
}
```
Run method `Test` in the module `Console` with three args

`php init.php Console Test '[{"x":"y"},1,"test"]'`

### Unit testing
Each module provides a class for testing.
```php
include_once 'init.php';
use PHPUnit\Framework\TestCase;
class ExampleTest extends TestCase {}
```
Run test of module `Example`

`php phpunit.phar protected/modules/Example/test.php`

### Files
```
/
├── /protected
│   ├── /import                         # Imported modules
│   ├── /modules                        # Installed modules
│   └── /render
│       └── /core
│           ├── /widgets
│           │   ├── css.php
│           │   ├── ie_warning.php
│           │   ├── js.php
│           │   └── page_loader.php
│           ├── error.php
│           ├── import.php
│           └── install.php
├── app.php                             # Core
├── conf.php                            # Config
└── init.php                            # Init script
```