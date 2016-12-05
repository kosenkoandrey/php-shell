# Favicon
Include website favicons

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)

### Files
```
/protected
├── /modules
│   └── /Favicon
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── install.php
│       ├── test.php
│       └── uninstall.php 
└── /render
    └── /favicon
        ├── android-icon-144x144.png
        ├── android-icon-192x192.png
        ├── android-icon-36x36.png
        ├── android-icon-48x48.png
        ├── android-icon-72x72.png
        ├── android-icon-96x96.png
        ├── apple-icon-114x114.png
        ├── apple-icon-120x120.png
        ├── apple-icon-144x144.png
        ├── apple-icon-152x152.png
        ├── apple-icon-180x180.png
        ├── apple-icon-57x57.png
        ├── apple-icon-60x60.png
        ├── apple-icon-72x72.png
        ├── apple-icon-76x76.png
        ├── apple-icon-precomposed.png
        ├── apple-icon.png
        ├── browserconfig.xml
        ├── favicon-16x16.png
        ├── favicon-32x32.png
        ├── favicon-96x96.png
        ├── favicon.ico
        ├── manifest.json
        ├── ms-icon-144x144.png
        ├── ms-icon-150x150.png
        ├── ms-icon-310x310.png
        ├── ms-icon-70x70.png
        └── widget.php

```

### Create icons
Use [online favicon generator](http://favicon-generator.org) for generate project icons.

### Examples
Use `APP::Render('favicon/widget')` for generate icons meta-tags

### WEB interfaces
```
/favicon.ico
/<brand>-icon-<size>.png
/apple-icon.png
/favicon-<size>.png
/manifest.json
/browserconfig.xml
```