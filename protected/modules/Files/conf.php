<?

return [
    'routes'  => [
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)\/preview\/(?P<file_id_hash>.*)',        'Files', 'PreviewFile'], // Preview file
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)\/groups\/add',                          'Files', 'AddFilesGroup'], // Add file group
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)\/groups\/(?P<group_id_hash>.*)\/edit',  'Files', 'EditFilesGroup'], // Edit file group
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)\/add(\?.*)?',                           'Files', 'AddFile'], // Add file
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)\/edit\/(?P<file_id_hash>.*)',           'Files', 'EditFile'], // Edit file
        ['admin\/files\/file\/(?P<group_sub_id_hash>.*)',                                       'Files', 'ManageFiles'], // Manage file
        ['admin\/files\/settings(\?.*)?',                                                       'Files', 'Settings'], // Files settings
        // API
        ['admin\/files\/api\/file\/add\.json(\?.*)?',                                           'Files', 'APIAddFile'], // [API] Add file
        ['admin\/files\/api\/file\/remove\.json(\?.*)?',                                        'Files', 'APIRemoveFile'], // [API] Remove file
        ['admin\/files\/api\/file\/update\.json(\?.*)?',                                        'Files', 'APIUpdateFile'], // [API] Update file
        ['admin\/files\/api\/file\/groups\/add\.json(\?.*)?',                                   'Files', 'APIAddFilesGroup'], // [API] Add file group
        ['admin\/files\/api\/file\/groups\/remove\.json(\?.*)?',                                'Files', 'APIRemoveFilesGroup'], // [API] Remove file group
        ['admin\/files\/api\/file\/groups\/update\.json(\?.*)?',                                'Files', 'APIUpdateFilesGroup'], // [API] Update file group
        ['admin\/files\/api\/file\/get\.json(\?.*)?',                                           'Files', 'APIGetFiles'], // [API] Get file
        ['admin\/files\/api\/settings\/update\.json(\?.*)?',                                    'Files', 'APIUpdateSettings'], // [API] Update files settings
    ],
    'fileCss' => [
        'video/mp4'       => 'zmdi-movie-alt',
        'application/pdf' => 'zmdi-file',
        'image/png'       => 'zmdi-image',
        'image/jpeg'      => 'zmdi-image',
        'image/jpg'       => 'zmdi-image',
    ]
];
