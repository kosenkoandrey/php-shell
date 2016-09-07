<?
return [
    'routes' => [
        ['admin\/cron(\?.*)?',                              'Cron', 'SelectSSHConnection'],     // Select SSH connection
        ['admin\/cron\/settings(\?.*)?',                    'Cron', 'Settings'],                // Settings
        
        ['admin\/cron\/jobs\/edit\/(?P<job_id_hash>.*)',    'Cron', 'EditJob'],                 // Edit job
        ['admin\/cron\/jobs\/(?P<ssh_id_hash>.*)\/add',     'Cron', 'AddJob'],                  // Add job
        ['admin\/cron\/jobs\/(?P<ssh_id_hash>.*)(\?.*)?',   'Cron', 'ManageJobs'],              // Manage jobs

        ['admin\/cron\/api\/jobs\/list\.json(\?.*)?',       'Cron', 'APIListJobs'],             // [API] List jobs
        ['admin\/cron\/api\/jobs\/add\.json(\?.*)?',        'Cron', 'APIAddJob'],               // [API] Add job
        ['admin\/cron\/api\/jobs\/update\.json(\?.*)?',     'Cron', 'APIUpdateJob'],            // [API] Update job
        ['admin\/cron\/api\/jobs\/remove\.json(\?.*)?',     'Cron', 'APIRemoveJob'],            // [API] Remove job
        ['admin\/cron\/api\/settings\/update\.json(\?.*)?', 'Cron', 'APIUpdateSettings'],       // [API] Update settings
    ]
];