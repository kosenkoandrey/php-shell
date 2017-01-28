<?
class Groups {
    
    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_groups_db_connection'
        ]);
    }
    
    public function Admin() {
        return APP::Render('groups/admin/nav', 'content');
    }

    
    public function ManageGroups() {
        APP::Render('groups/admin/index');
    }
    
    public function AddGroup() {
        APP::Render('groups/admin/add');
    }
    
    public function EditGroup() {
        $group_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);
        
        APP::Render('groups/admin/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['id', 'name', 'up_date'], 'groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        ));
    }
    
    public function Users() {
        APP::Render('groups/admin/users/index');
    }
    
    public function AddUser() {
        APP::Render('groups/admin/users/add');
    }

         
    public function APIGroupsList() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'groups.id', 
                'groups.name', 
                'groups.up_date',
                'COUNT(DISTINCT groups_users.user_id) as users'
            ], 
            'groups',
            $_POST['search'] ? [['name', 'LIKE', '%' . $_POST['search'] . '%' ]] : false,
            [
                'join/groups_users' => [
                    ['groups_users.group_id','=','groups.id']
                ]
            ],
            ['groups.id'], 
            false,
            [$_POST['sort_by'], $_POST['sort_direction']],
            $_POST['rows'] === -1 ? false : [($_POST['current'] - 1) * $_POST['rows'], $_POST['rows']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rows'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_groups_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'groups', $_POST['search'] ? [['name', 'LIKE', $_POST['search'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APICreateGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if ($out['status'] == 'success') {
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_groups_db_connection'], 'groups',
                [
                    'id' => 'NULL',
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );
            
            APP::Module('Triggers')->Exec('add_group', [
                'id' => $out['group_id'],
                'name' => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'groups',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_groups_db_connection'], 'groups',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('remove_group', [
                'id' => $_POST['id'],
                'result' => $out['count']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = (int) APP::Module('Crypt')->Decode($_POST['id']);
        
        if(!APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(*)'], 'groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        )){
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_groups_db_connection'], 'groups',
                [
                    'name' => $_POST['name']
                ],
                [['id', '=', $group_id, PDO::PARAM_INT]]
            );
        }
        
        APP::Module('Triggers')->Exec('update_group', [
            'id' => $group_id,
            'name' => $_POST['name']
        ]);
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUsersList() {
        $rows = [];
        
        $group_id = (int) APP::Module('Crypt')->Decode($_POST['group_id']);
        
        $where[] = ['group_id', '=', $group_id, PDO::PARAM_INT];
        $_POST['search'] ? $where[] = ['user_id', 'LIKE', '%' . $_POST['search'] . '%' ] : '';
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'groups_users.id', 
                'groups_users.group_id', 
                'groups_users.user_id', 
                'groups_users.cr_date',
                'users.email'
            ], 
            'groups_users',
            $where,
            [
                'join/users' => [
                    ['users.id', '=', 'groups_users.user_id']
                ]
            ],
            ['groups_users.id'], 
            false,
            [$_POST['sort_by'], $_POST['sort_direction']],
            $_POST['rows'] === -1 ? false : [($_POST['current'] - 1) * $_POST['rows'], $_POST['rows']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            $row['group_id_token'] = APP::Module('Crypt')->Encode($row['group_id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rows'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_groups_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'groups_users', $where)
        ]);
        exit;
    }
    
    public function APIAddUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if ($out['status'] == 'success') {
            foreach (APP::Module('DB')->Select(
                APP::Module('Users')->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                ['id'], 'users',
                [['email', 'IN', array_map(function($i) { return trim($i); }, explode("\n", $_POST['users']))]]
            ) as $user_id) {
                if (!APP::Module('DB')->Select(
                    $this->settings['module_groups_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'groups_users',
                    [
                        ['group_id', '=', $_POST['group_id'], PDO::PARAM_INT],
                        ['user_id', '=', $user_id, PDO::PARAM_INT]
                    ]
                )){
                    $out['user_id'] = APP::Module('DB')->Insert(
                        $this->settings['module_groups_db_connection'], 'groups_users',
                        [
                            'id' => 'NULL',
                            'group_id' => [$_POST['group_id'], PDO::PARAM_INT],
                            'user_id' => [$user_id, PDO::PARAM_STR],
                            'cr_date' => 'NOW()'
                        ]
                    );

                    APP::Module('Triggers')->Exec('add_user_to_group', [
                        'id' => $out['user_id'],
                        'group_id' => $_POST['group_id'],
                        'user_id' => $user_id
                    ]);
                }
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_groups_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'groups_users',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_groups_db_connection'], 'groups_users',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('remove_user_from_group', [
                'id' => $_POST['id'],
                'result' => $out['count']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }
    
}
