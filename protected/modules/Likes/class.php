<?
class Likes {

    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_likes_db_connection'
        ]);
    }
    
    
    public function Admin() {
        return APP::Render('likes/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('likes/admin/dashboard/index', 'return');
    }

    
    public function Get($type, $id, $users = 6) {
        return [
            'count' => APP::Module('DB')->Select(
                $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'likes_list', 
                [
                    ['object_type', '=', $type, PDO::PARAM_INT],
                    ['object_id', '=', $id, PDO::PARAM_INT]
                ]
            ),
            'users' => APP::Module('DB')->Select(
                $this->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                [
                    'likes_list.id',
                    'likes_list.user',
                    'users.email',
                    'users_about.value as username'
                ], 
                'likes_list', 
                [
                    ['object_type', '=', $type, PDO::PARAM_INT],
                    ['object_id', '=', $id, PDO::PARAM_INT]
                ],
                [
                    'join/users' => [['likes_list.user', '=', 'users.id']],
                    'left join/users_about' => [
                        ['users_about.user', '=', 'users.id'],
                        ['users_about.item', '=', '"username"']
                    ]
                ],
                ['likes_list.id'], 
                false, 
                ['id', 'desc'],
                $users ? [0, $users] : false
            ),
            'state' => APP::Module('Users')->user['id'] ? (bool) APP::Module('DB')->Select(
                $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'likes_list', 
                [
                    ['object_type', '=', $type, PDO::PARAM_INT],
                    ['object_id', '=', $id, PDO::PARAM_INT],
                    ['user', '=', APP::Module('Users')->user['id'], PDO::PARAM_INT]
                ]
            ) : false
        ];
    }
    
    
    public function ManageLikes() {
        APP::Render('likes/admin/index');
    }

    public function ManageLikesObjects() {
        APP::Render('likes/admin/objects/index');
    }
    
    public function AddLikeObject() {
        APP::Render('likes/admin/objects/add');
    }
    
    public function EditLikeObject() {
        $object_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['object_id_hash']);
        
        APP::Render('likes/admin/objects/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['name'], 'likes_objects',
            [['id', '=', $object_id, PDO::PARAM_INT]]
        ));
    }

    public function Settings() {
        APP::Render('likes/admin/settings');
    }
    
    
    public function APIDashboard() { 
        $out = [];
        
        for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
            $out[date('d-m-Y', $x)] = 0;
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['UNIX_TIMESTAMP(up_date)'], 'likes_list',
            [['UNIX_TIMESTAMP(up_date)', 'BETWEEN', $_POST['date']['from'] . ' AND ' . $_POST['date']['to']]]
        ) as $comment_date) {
            ++ $out[date('d-m-Y', $comment_date)];
        }
        
        foreach ($out as $key => $value) {
            $out[$key] = [strtotime($key) * 1000, $value];
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode(array_values($out));
        exit;
    }
    
    public function APIListLikes() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'likes_list.id', 
                'likes_list.user', 
                'likes_list.object_type', 
                'likes_list.object_id', 
                'likes_list.url', 
                'likes_list.up_date',
                'users.email',
                'likes_objects.name AS object_name'
            ], 'likes_list',
            $_POST['searchPhrase'] ? [['id', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            [
                'join/users' => [['likes_list.user', '=', 'users.id']],
                'join/likes_objects' => [['likes_list.object_type', '=', 'likes_objects.id']]
            ],
            ['likes_list.id'], 
            false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            $row['user_token'] = APP::Module('Crypt')->Encode($row['user']);
            
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_likes_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'likes_list', $_POST['searchPhrase'] ? [['id', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIToggleLike() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (isset($_POST['token'])) {
            $token = json_decode(APP::Module('Crypt')->Decode($_POST['token']), true);
            
            if (isset($token['type'])) {
                if (APP::Module('DB')->Select(
                    $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
                    ['COUNT(id)'], 'likes_objects',
                    [['id', '=', $token['type'], PDO::PARAM_INT]]
                )) {
                    if (!isset($token['id'])) {
                        $out['status'] = 'error';
                        $out['errors'][] = 4;
                    }
                } else {
                    $out['status'] = 'error';
                    $out['errors'][] = 3;
                }
            } else {
                $out['status'] = 'error';
                $out['errors'][] = 2;
            }
        } else {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            if ($like_id = (int) APP::Module('DB')->Select(
                $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
                ['id'], 'likes_list',
                [
                    ['user', '=', APP::Module('Users')->user['id'], PDO::PARAM_INT],
                    ['object_type', '=', $token['type'], PDO::PARAM_INT],
                    ['object_id', '=', $token['id'], PDO::PARAM_INT]
                ]
            )) {
                $out['action'] = 'remove';
                $out['count'] = APP::Module('DB')->Delete($this->settings['module_likes_db_connection'], 'likes_list', [['id', '=', $like_id, PDO::PARAM_INT]]);
                APP::Module('Triggers')->Exec('likes_remove_like', ['id' => $like_id, 'result' => $out['count']]);
            } else {
                $out['action'] = 'add';
                $out['id'] = APP::Module('DB')->Insert(
                    $this->settings['module_likes_db_connection'], ' likes_list',
                    [
                        'id' => 'NULL',
                        'user' => [APP::Module('Users')->user['id'], PDO::PARAM_INT],
                        'object_type' => [$token['type'], PDO::PARAM_INT],
                        'object_id' => [$token['id'], PDO::PARAM_INT],
                        'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR],
                        'up_date' => 'NOW()'
                    ]
                );

                $out['token'] = APP::Module('Crypt')->Encode($out['id']);

                APP::Module('Triggers')->Exec('likes_add_like', [
                    'id' => $out['id'],
                    'user' => APP::Module('Users')->user['id'],
                    'object_type' => $token['type'],
                    'object_id' => $token['id'],
                    'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR]
                ]);
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIListUsers() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (isset($_POST['token'])) {
            $token = json_decode(APP::Module('Crypt')->Decode($_POST['token']), true);
            
            if (isset($token['type'])) {
                if (APP::Module('DB')->Select(
                    $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
                    ['COUNT(id)'], 'likes_objects',
                    [['id', '=', $token['type'], PDO::PARAM_INT]]
                )) {
                    if (!isset($token['id'])) {
                        $out['status'] = 'error';
                        $out['errors'][] = 4;
                    }
                } else {
                    $out['status'] = 'error';
                    $out['errors'][] = 3;
                }
            } else {
                $out['status'] = 'error';
                $out['errors'][] = 2;
            }
        } else {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['users'] = APP::Module('DB')->Select(
                $this->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                [
                    'likes_list.id',
                    'likes_list.user',
                    'MD5(users.email) as e_token',
                    'users_about.value as username'
                ], 
                'likes_list', 
                [
                    ['likes_list.object_type', '=', $token['type'], PDO::PARAM_INT],
                    ['likes_list.object_id', '=', $token['id'], PDO::PARAM_INT]
                ],
                [
                    'join/users' => [['likes_list.user', '=', 'users.id']],
                    'left join/users_about' => [
                        ['users_about.user', '=', 'users.id'],
                        ['users_about.item', '=', '"username"']
                    ]
                ],
                ['likes_list.id'], 
                false, 
                ['likes_list.id', 'desc']
            );
            
            foreach ($out['users'] as $key => $value) {
                $out['users'][$key]['i_token'] = APP::Module('Crypt')->Encode($value['user']);
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveLike() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'likes_list',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_likes_db_connection'], 'likes_list', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('likes_remove_like', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIListLikesObjects() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name', 'up_date'], 'likes_objects',
            $_POST['searchPhrase'] ? [['name', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            false, false, false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_likes_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'likes_objects', $_POST['searchPhrase'] ? [['name', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIAddLikeObject() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['object_id'] = APP::Module('DB')->Insert(
                $this->settings['module_likes_db_connection'], ' likes_objects',
                [
                    'id' => 'NULL',
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );;
        
            APP::Module('Triggers')->Exec('likes_add_object', [
                'id' => $out['object_id'],
                'name' => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateLikeObject() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $object_id = APP::Module('Crypt')->Decode($_POST['object']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'likes_objects',
            [['id', '=', $object_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_likes_db_connection'], 'likes_objects', [
                'name' => $_POST['name']
            ], [['id', '=', $object_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('likes_update_object', [
                'id' => $object_id,
                'name' => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveLikeObject() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_likes_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'likes_objects',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_likes_db_connection'], 'likes_objects', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('likes_remove_object', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_likes_db_connection']], [['item', '=', 'module_likes_db_connection', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('likes_update_settings', [
            'db_connection' => $_POST['module_likes_db_connection']
        ]);
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'status' => 'success',
            'errors' => []
        ]);
        exit;
    }
    
}