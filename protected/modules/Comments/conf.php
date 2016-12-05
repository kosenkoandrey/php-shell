<?
return [
    'routes' => [
        ['admin\/comments(\?.*)?',                                  'Comments', 'ManageComments'],          // Manage comments
        ['admin\/comments\/edit\/(?P<message_id_hash>.*)',          'Comments', 'EditComment'],             // Edit comment
        ['admin\/comments\/objects(\?.*)?',                         'Comments', 'ManageCommentsObjects'],   // Manage comments objects
        ['admin\/comments\/objects\/add',                           'Comments', 'AddCommentObject'],        // Add comment object
        ['admin\/comments\/objects\/edit\/(?P<object_id_hash>.*)',  'Comments', 'EditCommentObject'],       // Edit comment object
        ['admin\/comments\/settings(\?.*)?',                        'Comments', 'Settings'],                // Settings
        
        ['comments\/api\/add\.json(\?.*)?',                         'Comments', 'APIAddComment'],           // [API] Add comment

        ['admin\/comments\/api\/dashboard\.json(\?.*)?',            'Comments', 'APIDashboard'],            // [API] Dashboard
        ['admin\/comments\/api\/list\.json(\?.*)?',                 'Comments', 'APIListComments'],         // [API] List comments
        ['admin\/comments\/api\/update\.json(\?.*)?',               'Comments', 'APIUpdateComment'],        // [API] Update comment
        ['admin\/comments\/api\/remove\.json(\?.*)?',               'Comments', 'APIRemoveComment'],        // [API] Remove comment
        ['admin\/comments\/api\/objects\/list\.json(\?.*)?',        'Comments', 'APIListCommentsObjects'],  // [API] List comments objects
        ['admin\/comments\/api\/objects\/add\.json(\?.*)?',         'Comments', 'APIAddCommentObject'],     // [API] Add comment object
        ['admin\/comments\/api\/objects\/update\.json(\?.*)?',      'Comments', 'APIUpdateCommentObject'],  // [API] Update comment object
        ['admin\/comments\/api\/objects\/remove\.json(\?.*)?',      'Comments', 'APIRemoveCommentObject'],  // [API] Remove comment object
        ['admin\/comments\/api\/settings\/update\.json(\?.*)?',     'Comments', 'APIUpdateSettings'],       // [API] Update settings
    ]
];