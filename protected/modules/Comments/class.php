<?
class Comments {

    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_comments_db_connection',
            'module_comments_files',
            'module_comments_path',
            'module_comments_mime'
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
        
        $files= [];
        foreach (APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'comments_files.comment_id', 
                'comments_files.type as file_type', 
                'comments_files.id as file_id', 
            ], 'comments_files',
            [
                ['comments_messages.object_type', '=', $type, PDO::PARAM_INT],
                ['comments_messages.object_id', '=', $id, PDO::PARAM_INT]
            ], 
            [
                'join/comments_messages' => [['comments_messages.id', '=', 'comments_files.comment_id']],
                'join/comments_objects' => [['comments_messages.object_type', '=', 'comments_objects.id']]
                
            ]
        ) as $comment) {
            $files[$comment['comment_id']][] = $comment;
        }

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
                'users_about.value AS username',
                'comments_files.type as file_type', 
                'comments_files.id as file_id', 
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
                'join/comments_objects' => [['comments_messages.object_type', '=', 'comments_objects.id']],
                'left join/comments_files' => [['comments_messages.id', '=', 'comments_files.comment_id']]
            ],
            ['comments_messages.id'], 
            false,
            ['comments_messages.id', 'ASC']
        ) as $comment) {
            if (!$comment['sub_id']) {
                $out['root'][$comment['id']] = $comment;
                $out['root'][$comment['id']]['files'] = isset($files[$comment['id']]) ? $files[$comment['id']] : [];
            } else {
                $out['children'][$comment['id']] = $comment;
                $out['children'][$comment['id']]['files'] = isset($files[$comment['id']]) ? $files[$comment['id']] : [];
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
        
        APP::Render('comments/admin/edit', 'include', 
            [
                'comment' => APP::Module('DB')->Select(
                    $this->settings['module_comments_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['message'], 'comments_messages',
                    [['id', '=', $message_id, PDO::PARAM_INT]]
                ),
                'files'   => APP::Module('DB')->Select(
                    $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['*'], 'comments_files', [['comment_id', '=', $message_id, PDO::PARAM_INT]]
                )
            ]
        );
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
    
    protected function FileUpload($file, $path) {
        $allowed      = [];
        $mime_allowed = preg_replace('~\r?\n~', "\n", $this->settings['module_comments_mime']);
        $filetypes    = explode("\n", $mime_allowed);
        foreach ($filetypes as $filetype) {
            $allowed[] = trim($filetype);
        }

        if (in_array($file['type'], $allowed) && $file['size']) {
            return move_uploaded_file($file['tmp_name'], $path);
        } else {
            return false;
        }
    }
    
    public function DownloadFile() {
        $file_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['file_id_hash']);

        $file = APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['title', 'type', 'id'], 'comments_files', 
            [['id', '=', $file_id, PDO::PARAM_INT]]
        );
        
        $file_ext = false;
        
        switch ($file['type']) {
            case 'video/mp4':
                $file_ext = 'mp4';
                break;
            case 'application/pdf':
                $file_ext = 'pdf';
                break;
            case 'image/jpeg':
                $file_ext = 'jpg';
                break;
        }
        
        $file_path = $this->settings['module_comments_path'] . '/' . $file['title'];
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'. $file['title'] .'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        readfile($file_path);
        exit;
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
            $message = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a target="_blank" href="$1://$2">$1://$2</a>$3', $message);
            
            $out['message'] = $message;
            $out['id'] = APP::Module('DB')->Insert(
                $this->settings['module_comments_db_connection'], 'comments_messages',
                [
                    'id' => 'NULL',
                    'sub_id' => [(int)$sub_id, PDO::PARAM_INT],
                    'user' => [APP::Module('Users')->user['id'], PDO::PARAM_INT],
                    'object_type' => [$token['type'], PDO::PARAM_INT],
                    'object_id' => [$token['id'], PDO::PARAM_INT],
                    'message' => [$message, PDO::PARAM_STR],
                    'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );
            $out['file'] = [];

            if ($this->settings['module_comments_files']){
                if (isset($_FILES['file']['name'])) {
                    foreach ($_FILES['file']['name'] as $key => $name){
                        if($_FILES['file']['tmp_name'][$key]){
                            $pathinfo = pathinfo($_FILES['file']['tmp_name'][$key] . '/' . $_FILES['file']['name'][$key]);

                            if(isset($pathinfo['extension'])){
                                $file_name = $out['id'] . '_' . APP::Module('Crypt')->Encode(time() . $key) . '.' . $pathinfo['extension'];

                                $out['file'][$key]['id'] = APP::Module('DB')->Insert(
                                    $this->settings['module_comments_db_connection'], 'comments_files', [
                                        'id' => 'NULL',
                                        'comment_id' => [$out['id'], PDO::PARAM_INT],
                                        'title' => [$file_name, PDO::PARAM_STR],
                                        'type' => [$_FILES['file']['type'][$key], PDO::PARAM_STR],
                                        'cr_date' => 'NOW()'
                                    ]
                                );
                                $out['file'][$key]['url'] = APP::Module('Routing')->root.'comments/download/'.APP::Module('Crypt')->Encode($out['file'][$key]['id']);
                                $out['file'][$key]['type'] = $_FILES['file']['type'][$key];

                                if (!$this->FileUpload([
                                    'type' => $_FILES['file']['type'][$key], 
                                    'size' => $_FILES['file']['size'][$key],
                                    'tmp_name' => $_FILES['file']['tmp_name'][$key]], 
                                    $this->settings['module_comments_path'] . $file_name
                                )) {
                                    APP::Module('DB')->Delete(
                                        $this->settings['module_comments_db_connection'], 'comments_files', 
                                        [['id', '=', $out['file'][$key]['id'], PDO::PARAM_INT]]
                                    );
                                    
                                    $out['file'][$key]['id'] = 0;
                                    $out['file'][$key]['url'] = '';
                                    $out['file'][$key]['type'] = '';
                                }
                            }
                        }
                    }
                }
            }
            
            $out['token'] = APP::Module('Crypt')->Encode($out['id']);
        
            APP::Module('Triggers')->Exec('comments_add_message', [
                'id' => $out['id'],
                'sub_id' => $sub_id,
                'user' => APP::Module('Users')->user['id'],
                'object_type' => $token['type'],
                'object_id' => $token['id'],
                'message' => $message,
                'url' => [$_SERVER['HTTP_REFERER'], PDO::PARAM_STR],
                'file' => $out['file']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIFileRemove(){
        $out['result'] = 'error';
        
        $file_id = (int) APP::Module('Crypt')->Decode($_POST['id']);

        if($file = APP::Module('DB')->Select(
            $this->settings['module_comments_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['title', 'type', 'id'], 'comments_files', 
            [['id', '=', $file_id, PDO::PARAM_INT]]
        )){
            APP::Module('DB')->Delete($this->settings['module_comments_db_connection'], 'comments_files', [['id', '=', $file_id, PDO::PARAM_INT]]);
            if (file_exists($this->settings['module_comments_path'] . $file['title'])) {
                unlink($this->settings['module_comments_path'] . $file['title']);
            }
            $out['result'] = 'success';
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
        
        $out['file'] = [];
        if(isset($_FILES['file']) && $this->settings['module_comments_files']){
            $file = $_FILES['file'];
            foreach ($file['name'] as $key => $name){
                if($file['tmp_name'][$key]){
                    $pathinfo = pathinfo($file['tmp_name'][$key] . '/' . $file['name'][$key]);
                    if(isset($pathinfo['extension'])){
                        $file_name = $message_id.'_'.APP::Module('Crypt')->Encode(time().$key).'.'.$pathinfo['extension'];

                        $out['file'][$key]['id'] = APP::Module('DB')->Insert(
                            $this->settings['module_comments_db_connection'], 'comments_files', [
                                'id'         => 'NULL',
                                'comment_id' => [$message_id, PDO::PARAM_INT],
                                'title'       => [$file_name, PDO::PARAM_STR],
                                'type'      => [$file['type'][$key], PDO::PARAM_STR],
                                'cr_date'    => 'NOW()'
                            ]
                        );
                        $out['file'][$key]['url'] = APP::Module('Routing')->root.'comments/download/'.APP::Module('Crypt')->Encode($out['file'][$key]['id']);
                        $out['file'][$key]['type'] = $file['type'][$key];

                        if (!$this->FileUpload(['type' => $file['type'][$key], 'size' => $file['size'][$key],'tmp_name' => $file['tmp_name'][$key]], $this->settings['module_comments_path'] . $file_name)) {
                            APP::Module('DB')->Delete(
                                $this->settings['module_comments_db_connection'], 'comments_files', [['id', '=', $out['file'][$key]['id'], PDO::PARAM_INT]]
                            );
                            $out['file'][$key]['id'] = 0;
                            $out['file'][$key]['url'] = '';
                            $out['file'][$key]['type'] = '';
                            $out['status'] = 'error';
                            $out['errors'][] = 3;
                        }
                    }
                }
            }
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
            
            APP::Module('DB')->Delete($this->settings['module_comments_db_connection'], 'comments_files', [['comment_id', '=', $_POST['id'], PDO::PARAM_INT]]);
            foreach(APP::Module('DB')->Select(
                $this->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['*'], 'comments_files',
                [['comment_id', '=', $_POST['id'], PDO::PARAM_INT]]
            ) as $file){
                if (file_exists($this->settings['module_comments_path'] . $file['title'])) {
                    unlink($this->settings['module_comments_path'] . $file['title']);
                }
            }
            
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
        APP::Module('Registry')->Update(['value' => $_POST['module_comments_mime']], [['item', '=', 'module_comments_mime', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_comments_path']], [['item', '=', 'module_comments_path', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_comments_files'])], [['item', '=', 'module_comments_files', PDO::PARAM_STR]]);
        
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