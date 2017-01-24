<?

return [
    'routes' => [
        ['admin\/quiz\/question(\?.*)?',                                                'Quiz', 'Questions'],
        ['admin\/quiz\/question\/add(\?.*)?',                                           'Quiz', 'AddQuestion'],
        ['admin\/quiz\/question\/edit\/(?P<question_id_hash>.*)',                       'Quiz', 'EditQuestion'],
        
        ['admin\/quiz\/answer\/add\/(?P<question_id_hash>.*)',                          'Quiz', 'AddAnswer'],
        ['admin\/quiz\/answer\/edit\/(?P<question_id_hash>.*)\/(?P<answer_id_hash>.*)', 'Quiz', 'EditAnswer'],
        ['admin\/quiz\/answer\/(?P<question_id_hash>.*)',                               'Quiz', 'Answers'],
        
        ['quiz\/answer\/(?P<answer_id_hash>.*)\/(?P<token>.*)',                         'Quiz', 'UserAnswer'],
        
        ['admin\/quiz\/api\/question\/list.json(\?.*)?',                                'Quiz', 'APIQuestionsList'],
        ['admin\/quiz\/api\/question\/create\.json(\?.*)?',                             'Quiz', 'APICreateQuestion'],
        ['admin\/quiz\/api\/question\/update\.json(\?.*)?',                             'Quiz', 'APIUpdateQuestion'],
        ['admin\/quiz\/api\/question\/remove\.json(\?.*)?',                             'Quiz', 'APIRemoveQuestion'],
        
        ['admin\/quiz\/api\/answer\/list.json(\?.*)?',                                  'Quiz', 'APIAnswersList'],
        ['admin\/quiz\/api\/answer\/create\.json(\?.*)?',                               'Quiz', 'APICreateAnswer'],
        ['admin\/quiz\/api\/answer\/update\.json(\?.*)?',                               'Quiz', 'APIUpdateAnswer'],
        ['admin\/quiz\/api\/answer\/remove\.json(\?.*)?',                               'Quiz', 'APIRemoveAnswer'],
    ]
];
