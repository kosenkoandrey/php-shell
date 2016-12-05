# Blog
Blogging platform which is beautifully designed and easy to use.

### Dependencies
- [Routing](https://github.com/evildevel/php-shell/tree/master/protected/modules/Routing)
- [Registry](https://github.com/evildevel/php-shell/tree/master/protected/modules/Registry)
- [DB](https://github.com/evildevel/php-shell/tree/master/protected/modules/DB)
- [Comments](https://github.com/evildevel/php-shell/tree/master/protected/modules/Comments)
- [Likes](https://github.com/evildevel/php-shell/tree/master/protected/modules/Likes)
- [Crypt](https://github.com/evildevel/php-shell/tree/master/protected/modules/Crypt)
- [Triggers](https://github.com/evildevel/php-shell/tree/master/protected/modules/Triggers)
- [Users](https://github.com/evildevel/php-shell/tree/master/protected/modules/Users)
- [SocialNetworks](https://github.com/evildevel/php-shell/tree/master/protected/modules/SocialNetworks)
- [Favicon](https://github.com/evildevel/php-shell/tree/master/protected/modules/Favicon)

### Files
```
/protected
├── /modules
│   └── /Blog
│       ├── MANIFEST
│       ├── README.md
│       ├── class.php
│       ├── conf.php
│       ├── test.php
│       ├── install.php
│       └── uninstall.php
└── /render
    ├── /blog
    │   ├── /widgets
    │   │   ├── /footer
    │   │   │   └── default.php
    │   │   ├── /header
    │   │   │   └── default.php
    │   │   ├── facebook_like.php
    │   │   ├── js.php
    │   │   ├── more_articles.php
    │   │   ├── subscribe_newsletter.php
    │   │   └── user_modal.php
    │   └── /admin
    │       ├── /articles
    │       │   ├── /groups
    │       │   │   ├── add.php
    │       │   │   └── edit.php
    │       │   ├── add.php
    │       │   ├── edit.php
    │       │   └── index.php
    │       ├── nav.php
    │       └── settings.php
    ├── /comments
    │   └── /widgets
    │       └── /blog
    │           ├── form.php
    │           └── list.php
    ├── /likes
    │   └── /widgets
    │       └── blog.php
    └── /social_networks
        └── /widgets
            └── blog.php
/public
└── blog
    └── <files>
```

### Constants
```php
// Get base blog URI
string Blog::URI
```

### Properties
```php
// Get full blog URI
string APP::Module('Blog')->uri

// Get settings
array APP::Module('Blog')->settings
```

### Methods
```php
// Get last articles by groups
array APP::Module('Blog')->LastArticlesByGroups(int $count)

// Get group articles
array APP::Module('Blog')->GroupArticles(string $group, int $offset, int $count)

// Get articles by tag
array APP::Module('Blog')->TagArticles(string $tag)

// Get related articles
array APP::Module('Blog')->RelatedArticles(int $group_id, int $count, array $exclude)

// Get most commented articles
array APP::Module('Blog')->MostCommentedArticles(int $count)

// Get random tags
array APP::Module('Blog')->RandomTags(int $count)
```

### Examples
```php
APP::Module('Blog')->LastArticlesByGroups(7);
APP::Module('Blog')->GroupArticles(5, 0, 10);
APP::Module('Blog')->TagArticles('abc');
APP::Module('Blog')->RelatedArticles(5, 4, [1, 2]);
APP::Module('Blog')->MostCommentedArticles(5);
APP::Module('Blog')->RandomTags(10);
```

### Triggers
- Add article
- Remove article
- Update article
- Add articles group
- Remove articles group
- Update articles group
- Update settings

### WEB interfaces
```
[Blog::URI]                                                               // Index
[Blog::URI]/about                                                         // About
[Blog::URI]/contacts                                                      // Contacts
[Blog::URI]/tos                                                           // Terms of Use
[Blog::URI]/privacy                                                       // Privacy and Policy
[Blog::URI]/tag/<tag>                                                     // Tag
[Blog::URI]/images/<object>/<size>/<uri>.(ext)                            // Image
[Blog::URI]/<token>                                                       // Group/article

/admin/blog/articles/<group_sub_id_hash>/groups/add                       // Add article group
/admin/blog/articles/<group_sub_id_hash>/groups/<group_id_hash>/edit      // Edit article group
/admin/blog/articles/<group_sub_id_hash>/add                              // Add article
/admin/blog/articles/<group_sub_id_hash>/edit/<article_id_hash>           // Edit article
/admin/blog/articles/<group_sub_id_hash>                                  // Manage articles

/admin/blog/settings                                                      // Blog settings

/admin/blog/api/articles/add.json                                         // [API] Add article
/admin/blog/api/articles/remove.json                                      // [API] Remove article
/admin/blog/api/articles/update.json                                      // [API] Update article
/admin/blog/api/articles/groups/add.json                                  // [API] Add article group
/admin/blog/api/articles/groups/remove.json                               // [API] Remove article group
/admin/blog/api/articles/groups/update.json                               // [API] Update article group
/admin/blog/api/articles/tags.json                                        // [API] Articles tags

/admin/blog/api/settings/update.json                                      // [API] Update blog settings
```