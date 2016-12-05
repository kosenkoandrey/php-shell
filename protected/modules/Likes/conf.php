<?
return [
    'routes' => [
        ['admin\/likes(\?.*)?',                                  'Likes', 'ManageLikes'],          // Manage likes
        ['admin\/likes\/objects(\?.*)?',                         'Likes', 'ManageLikesObjects'],   // Manage likes objects
        ['admin\/likes\/objects\/add',                           'Likes', 'AddLikeObject'],        // Add like object
        ['admin\/likes\/objects\/edit\/(?P<object_id_hash>.*)',  'Likes', 'EditLikeObject'],       // Edit like object
        ['admin\/likes\/settings(\?.*)?',                        'Likes', 'Settings'],             // Settings
        
        ['likes\/api\/toggle\.json(\?.*)?',                      'Likes', 'APIToggleLike'],        // [API] Toggle like
        ['likes\/api\/users\.json(\?.*)?',                       'Likes', 'APIListUsers'],         // [API] List users

        ['admin\/likes\/api\/dashboard\.json(\?.*)?',            'Likes', 'APIDashboard'],         // [API] Dashboard
        ['admin\/likes\/api\/list\.json(\?.*)?',                 'Likes', 'APIListLikes'],         // [API] List likes
        ['admin\/likes\/api\/remove\.json(\?.*)?',               'Likes', 'APIRemoveLike'],        // [API] Remove like
        ['admin\/likes\/api\/objects\/list\.json(\?.*)?',        'Likes', 'APIListLikesObjects'],  // [API] List likes objects
        ['admin\/likes\/api\/objects\/add\.json(\?.*)?',         'Likes', 'APIAddLikeObject'],     // [API] Add like object
        ['admin\/likes\/api\/objects\/update\.json(\?.*)?',      'Likes', 'APIUpdateLikeObject'],  // [API] Update like object
        ['admin\/likes\/api\/objects\/remove\.json(\?.*)?',      'Likes', 'APIRemoveLikeObject'],  // [API] Remove like object
        ['admin\/likes\/api\/settings\/update\.json(\?.*)?',     'Likes', 'APIUpdateSettings'],    // [API] Update settings
    ]
];