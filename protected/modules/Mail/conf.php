<?
return [
    'routes' => [
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)\/preview\/(?P<letter_id_hash>.*)',        'Mail', 'PreviewLetter'],          // Preview letter
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)\/groups\/add',                            'Mail', 'AddLettersGroup'],        // Add letters group
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)\/groups\/(?P<group_id_hash>.*)\/edit',    'Mail', 'EditLettersGroup'],       // Edit letters group
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)\/add(\?.*)?',                             'Mail', 'AddLetter'],              // Add letter
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)\/edit\/(?P<letter_id_hash>.*)',           'Mail', 'EditLetter'],             // Edit letter
        ['admin\/mail\/letters\/(?P<group_sub_id_hash>.*)',                                         'Mail', 'ManageLetters'],          // Manage letters
        ['admin\/mail\/senders\/(?P<group_sub_id_hash>.*)\/groups\/add',                            'Mail', 'AddSendersGroup'],        // Add senders group
        ['admin\/mail\/senders\/(?P<group_sub_id_hash>.*)\/groups\/(?P<group_id_hash>.*)\/edit',    'Mail', 'EditSendersGroup'],       // Edit senders group
        ['admin\/mail\/senders\/(?P<group_sub_id_hash>.*)\/add(\?.*)?',                             'Mail', 'AddSender'],              // Add sender
        ['admin\/mail\/senders\/(?P<group_sub_id_hash>.*)\/edit\/(?P<sender_id_hash>.*)',           'Mail', 'EditSender'],             // Edit sender
        ['admin\/mail\/senders\/(?P<group_sub_id_hash>.*)',                                         'Mail', 'ManageSenders'],          // Manage senders
        ['admin\/mail\/settings(\?.*)?',                                                            'Mail', 'Settings'],               // Mail settings
        
        ['admin\/mail\/api\/letters\/add\.json(\?.*)?',                 'Mail', 'APIAddLetter'],           // [API] Add letter
        ['admin\/mail\/api\/letters\/remove\.json(\?.*)?',              'Mail', 'APIRemoveLetter'],        // [API] Remove letter
        ['admin\/mail\/api\/letters\/update\.json(\?.*)?',              'Mail', 'APIUpdateLetter'],        // [API] Update letter
        ['admin\/mail\/api\/letters\/groups\/add\.json(\?.*)?',         'Mail', 'APIAddLettersGroup'],     // [API] Add letters group
        ['admin\/mail\/api\/letters\/groups\/remove\.json(\?.*)?',      'Mail', 'APIRemoveLettersGroup'],  // [API] Remove letters group
        ['admin\/mail\/api\/letters\/groups\/update\.json(\?.*)?',      'Mail', 'APIUpdateLettersGroup'],  // [API] Update letters group
        ['admin\/mail\/api\/senders\/add\.json(\?.*)?',                 'Mail', 'APIAddSender'],           // [API] Add sender
        ['admin\/mail\/api\/senders\/remove\.json(\?.*)?',              'Mail', 'APIRemoveSender'],        // [API] Remove sender
        ['admin\/mail\/api\/senders\/update\.json(\?.*)?',              'Mail', 'APIUpdateSender'],        // [API] Update sender
        ['admin\/mail\/api\/senders\/groups\/add\.json(\?.*)?',         'Mail', 'APIAddSendersGroup'],     // [API] Add senders group
        ['admin\/mail\/api\/senders\/groups\/remove\.json(\?.*)?',      'Mail', 'APIRemoveSendersGroup'],  // [API] Remove senders group
        ['admin\/mail\/api\/senders\/groups\/update\.json(\?.*)?',      'Mail', 'APIUpdateSendersGroup'],  // [API] Update senders group
        ['admin\/mail\/api\/settings\/update\.json(\?.*)?',             'Mail', 'APIUpdateSettings'],      // [API] Update mail settings
    ],
    'connection' => 'auto'
];