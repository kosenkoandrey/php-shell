<?
return [
    'routes' => [
        ['lights\/image(?P<id>.*)',                                'HotOrNot', 'PublicRedirect'],
        
        ['hotornot(\?.*)?',                                        'HotOrNot', 'Poll'],
        ['hotornot\/beta(\?.*)?',                                  'HotOrNot', 'PollBeta'],
        ['hotornot\/top(\?.*)?',                                   'HotOrNot', 'Top'],
        ['hotornot\/users(\?.*)?',                                 'HotOrNot', 'Users'],
        ['hotornot\/images\/(?P<id>[0-9]+)\/(?P<size>[0-9x]+)',    'HotOrNot', 'PeopleImage'],
        ['hotornot\/people\/api\/get\.json(\?.*)?',                'HotOrNot', 'APIGetPeople'],
        ['hotornot\/people\/api\/get_beta\.json(\?.*)?',           'HotOrNot', 'APIGetPeopleBeta'],
        ['hotornot\/people\/api\/rate\.json(\?.*)?',               'HotOrNot', 'APIRatePeople'],
        
        ['admin\/hotornot\/people(\?.*)?',                         'HotOrNot', 'ManagePeople'],
        ['admin\/hotornot\/people\/add(\?.*)?',                    'HotOrNot', 'AddPeople'],
        ['admin\/hotornot\/people\/edit\/(?P<people_id_hash>.*)',  'HotOrNot', 'EditPeople'],
        
        ['admin\/hotornot\/settings(\?.*)?',                       'HotOrNot', 'Settings'],

        // API
        ['admin\/hotornot\/people\/api\/search\.json(\?.*)?',      'HotOrNot', 'APISearchPeople'],
        ['admin\/hotornot\/people\/api\/action\.json(\?.*)?',      'HotOrNot', 'APISearchPeopleAction'],
        ['admin\/hotornot\/people\/api\/add\.json(\?.*)?',         'HotOrNot', 'APIAddPeople'],
        ['admin\/hotornot\/people\/api\/remove\.json(\?.*)?',      'HotOrNot', 'APIRemovePeople'],
        ['admin\/hotornot\/people\/api\/update\.json(\?.*)?',      'HotOrNot', 'APIUpdatePeople'],
        
        ['admin\/hotornot\/api\/settings\/update\.json(\?.*)?',    'HotOrNot', 'APIUpdateSettings'],
    ]
];