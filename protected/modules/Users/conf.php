<?
return [
    'routes' => [
        ['users\/actions\/(?P<action>login|register|forgot\-password|change\-password)(\?.*)?',  'Users', 'Actions'],   // Actions
        ['users\/login\/vk(\?.*)?',                                             'Users', 'LoginVK'],                    // Login via VK
        ['users\/login\/fb(\?.*)?',                                             'Users', 'LoginFB'],                    // Login via Facebook
        ['users\/login\/google(\?.*)?',                                         'Users', 'LoginGoogle'],                // Login via Google
        ['users\/login\/ya(\?.*)?',                                             'Users', 'LoginYA'],                    // Login via Yandex
        ['users\/login\/double\/(?P<return_hash>.*)',                           'Users', 'DoubleLoginForm'],            // Double login form
        ['users\/activate\/(?P<user_id_hash>.*)',                               'Users', 'Activate'],                   // User activation
        ['users\/profile(\?.*)?',                                               'Users', 'Profile'],                    // User profile
        ['users\/logout(\?.*)?',                                                'Users', 'Logout'],                     // Logout

        ['users\/api\/login\.json(\?.*)?',                                      'Users', 'APILogin'],                   // [API] Login
        ['users\/api\/double\-login\.json(\?.*)?',                              'Users', 'APIDoubleLogin'],             // [API] Double login
        ['users\/api\/logout\.json(\?.*)?',                                     'Users', 'APILogout'],                  // [API] Logout
        ['users\/api\/register\.json(\?.*)?',                                   'Users', 'APIRegister'],                // [API] Register
        ['users\/api\/reset\-password\.json(\?.*)?',                            'Users', 'APIResetPassword'],           // [API] Reset password
        ['users\/api\/change\-password\.json(\?.*)?',                           'Users', 'APIChangePassword'],          // [API] Change password
        
        ['admin\/users(\?.*)?',                                                 'Users', 'ManageUsers'],                // Manage users
        ['admin\/users\/add(\?.*)?',                                            'Users', 'AddUser'],                    // Add user
        ['admin\/users\/edit\/(?P<user_id_hash>.*)',                            'Users', 'EditUser'],                   // Edit user
        ['admin\/users\/oauth\/clients(\?.*)?',                                 'Users', 'SetupOAuthClients'],          // Setup oauth clients
        ['admin\/users\/notifications(\?.*)?',                                  'Users', 'SetupNotifications'],         // Setup notifications
        ['admin\/users\/services(\?.*)?',                                       'Users', 'SetupServices'],              // Setup services
        ['admin\/users\/auth(\?.*)?',                                           'Users', 'SetupAuth'],                  // Setup auth
        ['admin\/users\/passwords(\?.*)?',                                      'Users', 'SetupPasswords'],             // Setup passwords
        ['admin\/users\/timeouts(\?.*)?',                                       'Users', 'SetupTimeouts'],              // Setup timeouts
        ['admin\/users\/settings(\?.*)?',                                       'Users', 'SetupOther'],                 // Setup other
        ['admin\/users\/roles(\?.*)?',                                          'Users', 'ManageRoles'],                // Manage roles
        ['admin\/users\/roles\/add(\?.*)?',                                     'Users', 'AddRole'],                    // Add role
        ['admin\/users\/roles\/rules\/(?P<role_id_hash>.*)\/edit\/(?P<rule_id_hash>.*)(\?.*)?', 'Users', 'EditRule'],   // Edit rule
        ['admin\/users\/roles\/rules\/(?P<role_id_hash>.*)\/add(\?.*)?',        'Users', 'AddRule'],                    // Add rule
        ['admin\/users\/roles\/rules\/(?P<role_id_hash>.*)(\?.*)?',             'Users', 'ManageRules'],                // Manage rules of role
        
        ['admin\/users\/api\/list\.json(\?.*)?',                                'Users', 'APIListUsers'],                   // [API] List users
        ['admin\/users\/api\/add\.json(\?.*)?',                                 'Users', 'APIAddUser'],                     // [API] Add user
        ['admin\/users\/api\/remove\.json(\?.*)?',                              'Users', 'APIRemoveUser'],                  // [API] Remove user
        ['admin\/users\/api\/update\.json(\?.*)?',                              'Users', 'APIUpdateUser'],                  // [API] Update user
        ['admin\/users\/api\/roles\/list\.json(\?.*)?',                         'Users', 'APIListRoles'],                   // [API] List roles
        ['admin\/users\/api\/roles\/add\.json(\?.*)?',                          'Users', 'APIAddRole'],                     // [API] Add role
        ['admin\/users\/api\/roles\/remove\.json(\?.*)?',                       'Users', 'APIRemoveRole'],                  // [API] Remove role
        ['admin\/users\/api\/roles\/rules\/list\.json(\?.*)?',                  'Users', 'APIListRules'],                   // [API] Add rule
        ['admin\/users\/api\/roles\/rules\/add\.json(\?.*)?',                   'Users', 'APIAddRule'],                     // [API] Add rule
        ['admin\/users\/api\/roles\/rules\/update\.json(\?.*)?',                'Users', 'APIUpdateRule'],                  // [API] Update rule
        ['admin\/users\/api\/roles\/rules\/remove\.json(\?.*)?',                'Users', 'APIRemoveRule'],                  // [API] Remove rule
        ['admin\/users\/api\/oauth\/clients\/update\.json(\?.*)?',              'Users', 'APIUpdateOAuthClientSettings'],   // [API] Update oauth client settings
        ['admin\/users\/api\/notifications\/update\.json(\?.*)?',               'Users', 'APIUpdateNotificationsSettings'], // [API] Update notifications settings
        ['admin\/users\/api\/services\/update\.json(\?.*)?',                    'Users', 'APIUpdateServicesSettings'],      // [API] Update services settings
        ['admin\/users\/api\/auth\/update\.json(\?.*)?',                        'Users', 'APIUpdateAuthSettings'],          // [API] Update auth settings
        ['admin\/users\/api\/passwords\/update\.json(\?.*)?',                   'Users', 'APIUpdatePasswordsSettings'],     // [API] Update passwords settings
        ['admin\/users\/api\/timeouts\/update\.json(\?.*)?',                    'Users', 'APIUpdateTimeoutsSettings'],      // [API] Update timeouts settings
        ['admin\/users\/api\/settings\/update\.json(\?.*)?',                    'Users', 'APIUpdateOtherSettings'],         // [API] Update other settings
    ],
    'init' => true
];