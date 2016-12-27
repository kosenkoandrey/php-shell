<?
class Comments {

    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_comments_db_connection'
        ]);
    }
    
    
    public function Admin() {
        return APP::Render('comments/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('comments/admin/dashboard/index', 'return');
    }

    
    public function Get($type, $id) {
        $out = [
            'root' => [],
            'children' => [],
            'total' => 0
        ];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'comments_messages.id', 
                'comments_messages.sub_id', 
                'comments_messages.user', 
                'comments_messages.object_type', 
                'comments_messages.object_id', 
                'comments_messages.message', 
                'comments_messages.url', 
                'UNIX_TIMESTAMP(comments_messages.up_date) AS up_date',
                'users.email',
                'comments_objects.name AS object_name',
                'users_about.value AS username'
            ], 'comments_messages',
            [
                ['comments_messages.object_type', '=', $type, PDO::PARAM_INT],
                ['comments_messages.object_id', '=', $id, PDO::PARAM_INT]
            ], 
            [
                'join/users' => [['comments_messages.user', '=', 'users.id']],
                'left join/users_about' => [
                    ['users_about.user', '=', 'users.id'],
                    ['users_about.item', '=', '"username"']
                ],
                'join/comments_objects' => [['comments_messages.object_type', '=', 'comments_objects.id']]
            ],
            ['comments_messages.id'], 
            false,
            ['comments_messages.id', 'ASC']
        ) as $comment) {
            if (!$comment['sub_id']) {
                $out['root'][$comment['id']] = $comment;
            } else {
                $out['children'][$comment['id']] = $comment;
            }
            
            $out['total'] ++;
        }
        
        return $out;
    }
    
    
    public function ManageComments() {
        APP::Render('comments/admin/index');
    }

    public function EditComment() {
        $message_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['message_id_hash']);
        
        APP::Render('comments/admin/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['message'], 'comments_messages',
            [['id', '=', $message_id, PDO::PARAM_INT]]
        ));
    }

    public function ManageCommentsObjects() {
        APP::Render('comments/admin/objects/index');
    }
    
    public function AddCommentObject() {
        APP::Render('comments/admin/objects/add');
    }
    
    public function EditCommentObject() {
        $object_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['object_id_hash']);
        
        APP::Render('comments/admin/objects/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['name'], 'comments_objects',
            [['id', '=', $object_id, PDO::PARAM_INT]]
        ));
    }

    public function Settings() {
        APP::Render('comments/admin/settings');
    }
    
    
    public function APIDashboard() { 
        $out = [];
        
        for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
            $out[date('d-m-Y', $x)] = 0;
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['UNIX_TIMESTAMP(up_date)'], 'comments_messages',
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
    
    public function APIListComments() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'comments_messages.id', 
                'comments_messages.sub_id', 
                'comments_messages.user', 
                'comments_messages.object_type', 
                'comments_messages.object_id', 
                'comments_messages.url', 
                'comments_messages.message', 
                'comments_messages.up_date',
                'users.email',
                'comments_objects.name AS object_name'
            ], 'comments_messages',
            $_POST['searchPhrase'] ? [['id', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            [
                'join/users' => [['comments_messages.user', '=', 'users.id']],
                'join/comments_objects' => [['comments_messages.object_type', '=', 'comments_objects.id']]
            ],
            ['comments_messages.id'], 
            false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
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
            'total' => APP::Module('DB')->Select($this->settings['module_comments_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'comments_messages', $_POST['searchPhrase'] ? [['id', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIAddComment() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (isset($_POST['token'])) {
            $token = json_decode(APP::Module('Crypt')->Decode($_POST['token']), true);
            
            if (isset($token['type'])) {
                if (APP::Module('DB')->Select(
                    $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
                    ['COUNT(id)'], 'comments_objects',
                    [['id', '=', $token['type'], PDO::PARAM_INT]]
                )) {
                    if (isset($token['id'])) {
                        if (isset($token['login'])) {
                            if (array_search(APP::Module('Users')->user['role'], $token['login']) !== false) {
                                $out['status'] = 'error';
                                $out['errors'][] = 6;
                            }
                        } else {
                            $out['status'] = 'error';
                            $out['errors'][] = 5;
                        }
                    } else {
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
            
            $token = false;
        }

        if (isset($_POST['reply'])) {
            $sub_id = APP::Module('Crypt')->Decode($_POST['reply']);
            
            if ((int) $sub_id != 0) {
                if (!APP::Module('DB')->Select(
                    $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
                    ['COUNT(id)'], 'comments_messages',
                    [['id', '=', $sub_id, PDO::PARAM_INT]]
                )) {
                    $out['status'] = 'error';
                    $out['errors'][] = 8;
                }
            }
        } else {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }

        if (isset($_POST['message'])) {
            if (empty($_POST['message'])) {
                $out['status'] = 'error';
                $out['errors'][] = 10;
            }
        } else {
            $out['status'] = 'error';
            $out['errors'][] = 9;
        }

        if ($out['status'] == 'success') {
            $message = strip_tags($_POST['message']);
            
            $out['message'] = $message;
            $out['id'] = APP::Module('DB')->Insert(
                $this->settings['module_comments_db_connection'], ' comments_messages',
                [
                    'id' => 'NULL',
                    'sub_id' => [$sub_id, PDO::PARAM_INT],
                    'user' => [APP::Module('Users')->user['id'], PDO::PARAM_INT],
                    'object_type' => [$token['type'], PDO::PARAM_INT],
                    'object_id' => [$token['id'], PDO::PARAM_INT],
                    'message' => [$message, PDO::PARAM_STR],
                    'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );
            
            $out['token'] = APP::Module('Crypt')->Encode($out['id']);
        
            APP::Module('Triggers')->Exec('comments_add_message', [
                'id' => $out['id'],
                'sub_id' => $sub_id,
                'user' => APP::Module('Users')->user['id'],
                'object_type' => $token['type'],
                'object_id' => $token['id'],
                'message' => $message,
                'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR]
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateComment() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $message_id = APP::Module('Crypt')->Decode($_POST['comment']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'comments_messages',
            [['id', '=', $message_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['message'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_comments_db_connection'], 'comments_messages', [
                'message' => $_POST['message']
            ], [['id', '=', $message_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('comments_update_message', [
                'id' => $message_id,
                'message' => $_POST['message']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveComment() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'comments_messages',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_comments_db_connection'], 'comments_messages', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('comments_remove_message', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIListCommentsObjects() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name', 'up_date'], 'comments_objects',
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
            'total' => APP::Module('DB')->Select($this->settings['module_comments_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'comments_objects', $_POST['searchPhrase'] ? [['name', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIAddCommentObject() {
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
                $this->settings['module_comments_db_connection'], ' comments_objects',
                [
                    'id' => 'NULL',
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );;
        
            APP::Module('Triggers')->Exec('comments_add_object', [
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
    
    public function APIUpdateCommentObject() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $object_id = APP::Module('Crypt')->Decode($_POST['object']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'comments_objects',
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
            APP::Module('DB')->Update($this->settings['module_comments_db_connection'], 'comments_objects', [
                'name' => $_POST['name']
            ], [['id', '=', $object_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('comments_update_object', [
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

    public function APIRemoveCommentObject() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'comments_objects',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_comments_db_connection'], 'comments_objects', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('comments_remove_object', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_comments_db_connection']], [['item', '=', 'module_comments_db_connection', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('comments_update_settings', [
            'db_connection' => $_POST['module_comments_db_connection']
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