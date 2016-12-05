<?
return [
    'routes' => [
        [preg_quote(Blog::URI, '/') . '(\?.*)?',                                                     'Blog', 'Index'],                // Index
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'about(\?.*)?',                      'Blog', 'About'],                // About
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'contacts(\?.*)?',                   'Blog', 'Contacts'],             // Contacts
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'tos(\?.*)?',                        'Blog', 'TOS'],                  // Terms of Use
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'privacy(\?.*)?',                    'Blog', 'Privacy'],              // Privacy
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'tag\/(?P<tag>[\w\s]+)(\?.*)?',      'Blog', 'Tag'],                  // Tags
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . 'images\/(?P<object>groups|articles)\/(?P<size>[0-9x]+)\/(?P<uri>[a-z0-9\-]+)\.[a-z]+(\?.*)?', 'Blog', 'Image'], // Image
        [(Blog::URI ? preg_quote(Blog::URI, '/') . '\/' : '') . '(?P<token>[a-z0-9\-]+)(\?.*)?',     'Blog', 'Page'],                 // Groups and articles

        ['admin\/blog\/articles\/(?P<group_sub_id_hash>.*)\/groups\/add',                            'Blog', 'AddArticlesGroup'],     // Add article group
        ['admin\/blog\/articles\/(?P<group_sub_id_hash>.*)\/groups\/(?P<group_id_hash>.*)\/edit',    'Blog', 'EditArticlesGroup'],    // Edit article group
        ['admin\/blog\/articles\/(?P<group_sub_id_hash>.*)\/add(\?.*)?',                             'Blog', 'AddArticle'],           // Add article
        ['admin\/blog\/articles\/(?P<group_sub_id_hash>.*)\/edit\/(?P<article_id_hash>.*)',          'Blog', 'EditArticle'],          // Edit article
        ['admin\/blog\/articles\/(?P<group_sub_id_hash>.*)',                                         'Blog', 'ManageArticles'],       // Manage articles
        
        ['admin\/blog\/settings(\?.*)?',                                                             'Blog', 'Settings'],             // Blog settings

        // API
        
        ['admin\/blog\/api\/articles\/add\.json(\?.*)?',                 'Blog', 'APIAddArticle'],            // [API] Add article
        ['admin\/blog\/api\/articles\/remove\.json(\?.*)?',              'Blog', 'APIRemoveArticle'],         // [API] Remove article
        ['admin\/blog\/api\/articles\/update\.json(\?.*)?',              'Blog', 'APIUpdateArticle'],         // [API] Update article
        ['admin\/blog\/api\/articles\/groups\/add\.json(\?.*)?',         'Blog', 'APIAddArticlesGroup'],      // [API] Add article group
        ['admin\/blog\/api\/articles\/groups\/remove\.json(\?.*)?',      'Blog', 'APIRemoveArticlesGroup'],   // [API] Remove article group
        ['admin\/blog\/api\/articles\/groups\/update\.json(\?.*)?',      'Blog', 'APIUpdateArticlesGroup'],   // [API] Update article group
        ['admin\/blog\/api\/articles\/tags\.json(\?.*)?',                'Blog', 'APIArticlesTags'],          // [API] Articles tags
        
        ['admin\/blog\/api\/settings\/update\.json(\?.*)?',              'Blog', 'APIUpdateSettings'],        // [API] Update blog settings
    ]
];