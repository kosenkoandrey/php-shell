<?
class Polls {
    
    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_polls_db_connection'
        ]);
    }

    public function Poll($id) {
        return APP::Module('DB')->Select(
            APP::Module('Polls')->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['id', 'name', 'up_date'], 'polls',
            [['id', '=', $id, PDO::PARAM_INT]]
        );
    }
    
    public function Colors() {
        $poll = 1;
        $step = 1;

        $token = json_decode(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['token']), true);

        if (!APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'users',
            [['email', '=', isset($token['email']) ? $token['email'] : 0, PDO::PARAM_STR]]
        )) {
            APP::Render(
                'polls/error', 
                'include', 
                APP::Module('DB')->Select(
                    $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['name'], 'polls',
                    [['id', '=', $poll, PDO::PARAM_INT]]
                )
            );
            exit;
        }
        
        $user_id = APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['email', '=', $token['email'], PDO::PARAM_STR]]
        );
        
        ////////////////////////////////////////////////////////////////////////
        
        if (isset($_POST['answers'])) {
            foreach ($_POST['answers'] as $key => $value) {
                if (APP::Module('DB')->Select(
                    $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'polls_answers_users',
                    [
                        ['user', '=', $user_id, PDO::PARAM_INT],
                        ['question', '=', $key, PDO::PARAM_INT]
                    ]
                )) {
                    APP::Module('DB')->Update(
                        $this->settings['module_polls_db_connection'], 'polls_answers_users',
                        ['answer' => $value],
                        [
                            ['user', '=', $user_id, PDO::PARAM_INT],
                            ['question', '=', $key, PDO::PARAM_INT]
                        ]
                    );
                } else {
                    APP::Module('DB')->Insert(
                        $this->settings['module_polls_db_connection'], 'polls_answers_users',
                        Array(
                            'id' => 'NULL',
                            'user' => [$user_id, PDO::PARAM_INT],
                            'question' => [$key, PDO::PARAM_INT],
                            'answer' => [$value, PDO::PARAM_STR],
                            'up_date' => 'NOW()'
                        )
                    );
                }
            }
        }
        
        if (isset($_POST['step'])) {
            switch ($_POST['step']) {
                case 1:
                    $step = 2;
                    break;
                case 2:
                    if (!APP::Module('DB')->Select(
                        $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['COUNT(id)'], 'polls_users',
                        [
                            ['user', '=', $user_id, PDO::PARAM_INT],
                            ['poll', '=', $poll, PDO::PARAM_INT]
                        ]
                    )) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_polls_db_connection'], 'polls_users',
                            Array(
                                'id' => 'NULL',
                                'user' => [$user_id, PDO::PARAM_INT],
                                'poll' => [$poll, PDO::PARAM_INT],
                                'up_date' => 'NOW()'
                            )
                        );
                    }
                    break;
            }
        }
        
        ////////////////////////////////////////////////////////////////////////
        
        if (APP::Module('DB')->Select(
            $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'polls_users',
            [
                ['user', '=', $user_id, PDO::PARAM_INT],
                ['poll', '=', $poll, PDO::PARAM_INT]
            ]
        )) {
            APP::Render(
                'polls/colors/complete', 
                'include', 
                APP::Module('DB')->Select(
                    $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['name'], 'polls',
                    [['id', '=', $poll, PDO::PARAM_INT]]
                )
            );
        } else {
            APP::Render(
                'polls/colors/step', 
                'include', 
                [
                    'step' => $step,
                    'poll' => APP::Module('DB')->Select(
                        $this->settings['module_polls_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['name'], 'polls',
                        [['id', '=', $poll, PDO::PARAM_INT]]
                    )
                ]
            );
        }
    }

}