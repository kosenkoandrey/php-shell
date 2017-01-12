<?
class Tunnels {

    public $settings;
    
    public $t_factors;
    public $t_actions;
    public $t_conditions;
    public $t_timeouts;
    
    private $search;
    private $actions;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_tunnels_db_connection',
            'module_tunnels_max_execution_time',
            'module_tunnels_execution_tunnels_number',
            'module_tunnels_active_user_roles',
            'module_tunnels_max_run_timeout',
            'module_tunnels_default_queue_timeout',
            'module_tunnels_max_queue_timeout',
            'module_tunnels_indoctrination_lifetime',
            'module_tunnels_resend_acrivation_timeout',
            'module_tunnels_memory_limit',
            'module_tunnels_tmp_dir'
        ]);
        
        $this->search = new TunnelsSearch();
        $this->actions = new TunnelsActions();
    }

    public function Admin() {
        return APP::Render('tunnels/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('tunnels/admin/dashboard/index', 'return');
    }
    
    
    public function Search($rules) {
        $out = [];

        foreach ((array) $rules['rules'] as $rule) {
            $out[] = array_flip((array) $this->search->{$rule['method']}($rule['settings']));
        }
        
        if (array_key_exists('children', (array) $rules)) {
            $out[] = array_flip((array) $this->Search($rules['children']));
        }
        
        if (count($out) > 1) {
            switch ($rules['logic']) {
                case 'intersect': return array_keys((array) call_user_func_array('array_intersect_key', $out)); break;
                case 'merge': return array_keys((array) call_user_func_array('array_replace', $out)); break;
            }
        } else {
            return array_keys($out[0]);
        }
    }
    
    
    public function ManageTunnels() {
        APP::Render('tunnels/admin/index');
    }
    
    public function AddTunnel() {
        APP::Render('tunnels/admin/add');
    }
    
    public function EditTunnel() {
        $tunnel_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['tunnel_id_hash']);
        
        APP::Render(
            'tunnels/admin/edit', 'include', 
            [
                'tunnel' => APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['type', 'state', 'name', 'description', 'factors', 'style'], 'tunnels',
                    [['id', '=', $tunnel_id, PDO::PARAM_INT]]
                ),
            ]
        );
    }
    
    public function TunnelScheme() {
        APP::Render(
            'tunnels/admin/scheme', 'include', 
            [
                'tunnels' => APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    ['id', 'type', 'state', 'name', 'description'], 'tunnels'
                ),
            ]
        );
    }
    
    public function Settings() {
        APP::Render('tunnels/admin/settings');
    }
    
    
    public function APIDashboard() { 
        $out = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'type', 'state', 'name'], 'tunnels'
        ) as $tunnel) {
            $out[$tunnel['type']][$tunnel['id']] = [
                'hash' => APP::Module('Crypt')->Encode($tunnel['id']),
                'state' => $tunnel['state'],
                'name' => $tunnel['name'],
                'subscribers' => [
                    'active' => [
                        APP::Module('DB')->Select(
                            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                            ['COUNT(id)'], 'tunnels_users',
                            [
                                ['tunnel_id', '=', $tunnel['id'], PDO::PARAM_INT],
                                ['state', '=', "active", PDO::PARAM_STR]
                            ]
                        ), 
                        APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_tunnels","settings":{"value":"' . $tunnel['id'] . '","state":"active"}}]}')
                    ],
                    'total' => [
                        APP::Module('DB')->Select(
                            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                            ['COUNT(id)'], 'tunnels_users',
                            [
                                ['tunnel_id', '=', $tunnel['id'], PDO::PARAM_INT]
                            ]
                        ), 
                        APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_tunnels","settings":{"value":"' . $tunnel['id'] . '","state":"%"}}]}')
                    ]
                ]
            ];
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
    }

    public function APISearchTunnels() {
        $request = json_decode(file_get_contents('php://input'), true);
        $out = $this->Search(json_decode($request['search'], 1));
        $rows = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'type', 'state', 'name', 'description'], 'tunnels',
            [['id', 'IN', $out, PDO::PARAM_INT]], 
            false, false, false,
            [$request['sort_by'], $request['sort_direction']],
            [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['tunnel_id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $request['current'],
            'rowCount' => $request['rows'],
            'rows' => $rows,
            'total' => count($out)
        ]);
        exit;
    }
    
    public function APISearchAction() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($this->actions->{$_POST['action']}($this->Search(json_decode($_POST['rules'], 1)), isset($_POST['settings']) ? $_POST['settings'] : false));
        exit;
    }
    
    public function APIAddTunnel() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if ($out['status'] == 'success') {
            $out['tunnel_id'] = APP::Module('DB')->Insert(
                $this->settings['module_tunnels_db_connection'], 'tunnels',
                [
                    'id' => 'NULL',
                    'type' => [$_POST['type'], PDO::PARAM_STR],
                    'state' => [$_POST['state'], PDO::PARAM_STR],
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'description' => [$_POST['description'], PDO::PARAM_STR],
                    'factors' => [$_POST['factors'], PDO::PARAM_STR],
                    'style' => [$_POST['style'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );
            
            APP::Module('Triggers')->Exec('add_manual_tunnel', [
                'id' => $out['tunnel_id'],
                'type' => $_POST['type'],
                'state' => $_POST['state'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'factors' => $_POST['factors'],
                'style' => $_POST['style']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveTunnel() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'tunnels',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_tunnels_db_connection'], 'tunnels',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('remove_tunnel', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateTunnel() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $tunnel_id = is_numeric($_POST['id']) ? $_POST['id'] : APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select($this->settings['module_tunnels_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'tunnels', [['id', '=', $tunnel_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels', [
                'type' => $_POST['type'],
                'state' => $_POST['state'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'factors' => $_POST['factors'],
                'style' => $_POST['style']
            ], [
                ['id', '=', $tunnel_id, PDO::PARAM_INT]
            ]);

            APP::Module('Triggers')->Exec('update_tunnel', [
                'id' => $tunnel_id,
                'type' => $_POST['type'],
                'state' => $_POST['state'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'factors' => $_POST['factors'],
                'style' => $_POST['style']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_db_connection']], [['item', '=', 'module_tunnels_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_max_execution_time']], [['item', '=', 'module_tunnels_max_execution_time', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_execution_tunnels_number']], [['item', '=', 'module_tunnels_execution_tunnels_number', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => implode(',', $_POST['module_tunnels_active_user_roles'])], [['item', '=', 'module_tunnels_active_user_roles', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_max_run_timeout']], [['item', '=', 'module_tunnels_max_run_timeout', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_default_queue_timeout']], [['item', '=', 'module_tunnels_default_queue_timeout', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_max_queue_timeout']], [['item', '=', 'module_tunnels_max_queue_timeout', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_indoctrination_lifetime']], [['item', '=', 'module_tunnels_indoctrination_lifetime', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_resend_acrivation_timeout']], [['item', '=', 'module_tunnels_resend_acrivation_timeout', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_memory_limit']], [['item', '=', 'module_tunnels_memory_limit', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_tunnels_tmp_dir']], [['item', '=', 'module_tunnels_tmp_dir', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('update_tunnels_settings', [
            'db_connection' => $_POST['module_tunnels_db_connection'],
            'max_execution_time' => $_POST['module_tunnels_max_execution_time'],
            'execution_tunnels_number' => $_POST['module_tunnels_execution_tunnels_number'],
            'active_user_roles' => $_POST['module_tunnels_active_user_roles'],
            'max_run_timeout' => $_POST['module_tunnels_max_run_timeout'],
            'default_queue_timeout' => $_POST['module_tunnels_default_queue_timeout'],
            'max_queue_timeout' => $_POST['module_tunnels_max_queue_timeout'],
            'indoctrination_lifetime' => $_POST['module_tunnels_indoctrination_lifetime'],
            'resend_acrivation_timeout' => $_POST['module_tunnels_resend_acrivation_timeout'],
            'memory_limit' => $_POST['module_tunnels_memory_limit'],
            'tmp_dir' => $_POST['module_tunnels_tmp_dir']
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
    
    
    public function APIScheme() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'process' => APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'type', 'state', 'name', 'description', 'factors', 'style'], 'tunnels',
                [['id', '=', $_POST['tunnel_id'], PDO::PARAM_INT]]
            ),
            'actions' => APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'tunnel_id', 'action', 'settings', 'child_object', 'style', 'comment'], 'tunnels_actions',
                [['tunnel_id', '=', $_POST['tunnel_id'], PDO::PARAM_INT]]
            ),
            'conditions' => APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'tunnel_id', 'rules', 'child_object_y', 'child_object_n', 'style', 'comment'], 'tunnels_conditions',
                [['tunnel_id', '=', $_POST['tunnel_id'], PDO::PARAM_INT]]
            ),
            'timeouts' => APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'tunnel_id', 'timeout', 'timeout_type', 'child_object', 'style', 'comment'], 'tunnels_timeouts',
                [['tunnel_id', '=', $_POST['tunnel_id'], PDO::PARAM_INT]]
            ),
            'comments' => APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'tunnel_id', 'comment', 'child_object', 'style'], 'tunnels_comments',
                [['tunnel_id', '=', $_POST['tunnel_id'], PDO::PARAM_INT]]
            )
        ]);
    }
    
    
    public function APICreateAction() {
        $id = APP::Module('DB')->Insert(
            $this->settings['module_tunnels_db_connection'], 'tunnels_actions',
            [
                'id'            => 'NULL',
                'tunnel_id'     => [$_POST['action']['tunnel_id'], PDO::PARAM_INT],
                'action'        => [$_POST['action']['action'], PDO::PARAM_STR],
                'settings'      => [$_POST['action']['settings'], PDO::PARAM_STR],
                'child_object'  => [$_POST['action']['child_object'], PDO::PARAM_STR],
                'style'         => [$_POST['action']['style'], PDO::PARAM_STR],
                'comment'       => [$_POST['action']['comment'], PDO::PARAM_STR],
                'up_date'       => 'NOW()'
            ]
        );
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($id ? [
            'result' => 'success',
            'action' => [
                'id' => $id,
                'id_hash' => APP::Module('Crypt')->Encode($id)
            ]
        ] : [
            'result' => 'error',
        ]);
    }
    
    public function APIUpdateAction() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_actions', [
                'tunnel_id'     => isset($_POST['action']['tunnel_id']) ? $_POST['action']['tunnel_id'] : '',
                'action'        => isset($_POST['action']['action']) ? $_POST['action']['action'] : '',
                'settings'      => isset($_POST['action']['settings']) ? $_POST['action']['settings'] : '',
                'child_object'  => isset($_POST['action']['child_object']) ? $_POST['action']['child_object'] : '',
                'style'         => isset($_POST['action']['style']) ? $_POST['action']['style'] : '',
                'comment'       => isset($_POST['action']['comment']) ? $_POST['action']['comment'] : ''
            ], [
                ['id', '=', $_POST['action']['id'], PDO::PARAM_INT]
            ]),
            'action' => $_POST['action']
        ]);
    }
    
    public function APIRemoveAction() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Delete(
                $this->settings['module_tunnels_db_connection'], 'tunnels_actions',
                [['id', '=', $_POST['action_id'], PDO::PARAM_INT]]
            )
        ]);
    }
    
    
    public function APICreateCondition() {
        $id = APP::Module('DB')->Insert(
            $this->settings['module_tunnels_db_connection'], 'tunnels_conditions',
            [
                'id'                => 'NULL',
                'tunnel_id'         => [$_POST['condition']['tunnel_id'], PDO::PARAM_INT],
                'rules'             => [$_POST['condition']['rules'], PDO::PARAM_STR],
                'child_object_y'    => [$_POST['condition']['child_object_y'], PDO::PARAM_STR],
                'child_object_n'    => [$_POST['condition']['child_object_n'], PDO::PARAM_STR],
                'style'             => [$_POST['condition']['style'], PDO::PARAM_STR],
                'comment'           => [$_POST['condition']['comment'], PDO::PARAM_STR],
                'up_date'           => 'NOW()'
            ]
        );
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($id ? [
            'result' => 'success',
            'condition' => [
                'id' => $id,
                'id_hash' => APP::Module('Crypt')->Encode($id)
            ]
        ] : [
            'result' => 'error',
        ]);
    }
    
    public function APIUpdateCondition() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_conditions', [
                'tunnel_id'         => isset($_POST['condition']['tunnel_id']) ? $_POST['condition']['tunnel_id'] : '',
                'rules'             => isset($_POST['condition']['rules']) ? $_POST['condition']['rules'] : '',
                'child_object_y'    => isset($_POST['condition']['child_object_y']) ? $_POST['condition']['child_object_y'] : '',
                'child_object_n'    => isset($_POST['condition']['child_object_n']) ? $_POST['condition']['child_object_n'] : '',
                'style'             => isset($_POST['condition']['style']) ? $_POST['condition']['style'] : '',
                'comment'           => isset($_POST['condition']['comment']) ? $_POST['condition']['comment'] : ''
            ], [
                ['id', '=', $_POST['condition']['id'], PDO::PARAM_INT]
            ]),
            'condition' => $_POST['condition']
        ]);
    }
    
    public function APIRemoveCondition() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Delete(
                $this->settings['module_tunnels_db_connection'], 'tunnels_conditions',
                [['id', '=', $_POST['condition_id'], PDO::PARAM_INT]]
            )
        ]);
    }
    
    
    public function APICreateTimeout() {
        $id = APP::Module('DB')->Insert(
            $this->settings['module_tunnels_db_connection'], 'tunnels_timeouts',
            [
                'id'            => 'NULL',
                'tunnel_id'     => [$_POST['timeout']['tunnel_id'], PDO::PARAM_INT],
                'timeout'       => [$_POST['timeout']['timeout'], PDO::PARAM_STR],
                'timeout_type'  => [$_POST['timeout']['timeout_type'], PDO::PARAM_STR],
                'child_object'  => [$_POST['timeout']['child_object'], PDO::PARAM_STR],
                'style'         => [$_POST['timeout']['style'], PDO::PARAM_STR],
                'comment'       => [$_POST['timeout']['comment'], PDO::PARAM_STR],
                'up_date'       => 'NOW()'
            ]
        );
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($id ? [
            'result' => 'success',
            'timeout' => [
                'id' => $id,
                'id_hash' => APP::Module('Crypt')->Encode($id)
            ]
        ] : [
            'result' => 'error',
        ]);
    }
    
    public function APIUpdateTimeout() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_timeouts', [
                'tunnel_id'     => isset($_POST['timeout']['tunnel_id']) ? $_POST['timeout']['tunnel_id'] : '',
                'timeout'       => isset($_POST['timeout']['timeout']) ? $_POST['timeout']['timeout'] : '',
                'timeout_type'  => isset($_POST['timeout']['timeout_type']) ? $_POST['timeout']['timeout_type'] : '',
                'child_object'  => isset($_POST['timeout']['child_object']) ? $_POST['timeout']['child_object'] : '',
                'style'         => isset($_POST['timeout']['style']) ? $_POST['timeout']['style'] : '',
                'comment'       => isset($_POST['timeout']['comment']) ? $_POST['timeout']['comment'] : ''
            ], [
                ['id', '=', $_POST['timeout']['id'], PDO::PARAM_INT]
            ]),
            'timeout' => $_POST['timeout']
        ]);
    }
    
    public function APIRemoveTimeout() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'result' => APP::Module('DB')->Delete(
                $this->settings['module_tunnels_db_connection'], 'tunnels_timeouts',
                [['id', '=', $_POST['timeout_id'], PDO::PARAM_INT]]
            )
        ]);
    }

    
    public function APISubscribe() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($this->Subscribe([
            'email'             => $_POST['email'],
            'tunnel'            => $_POST['tunnel'],
            'activation'        => $_POST['activation'],
            'source'            => isset($_POST['source']) ? substr($_POST['source'], 0, 100) : 'APISubscribe',
            'roles_tunnel'      => isset($_POST['roles_tunnel']) ? $_POST['roles_tunnel'] : false,
            'welcome'           => isset($_POST['welcome']) ? $_POST['welcome'] : false,
            'queue_timeout'     => isset($_POST['queue_timeout']) ? $_POST['queue_timeout'] : $this->settings['module_tunnels_default_queue_timeout'],
            'complete_tunnels'  => isset($_POST['complete_tunnels']) ? $_POST['complete_tunnels'] : false,
            'pause_tunnels'     => isset($_POST['pause_tunnels']) ? $_POST['pause_tunnels'] : false,
            'input_data'        => isset($_POST['input_data']) ? $_POST['input_data'] : [],
            'about_user'        => isset($_POST['about_user']) ? $_POST['about_user'] : [],
            'auto_save_about'   => isset($_POST['auto_save_about']) ? $_POST['auto_save_about'] : false,
            'save_utm'          => isset($_POST['save_utm']) ? $_POST['save_utm'] : false
        ]));
    }
    
    public function TestSubscribe() {
        ?>
        <h1>Test subscribe</h1>
        <form method="post" action="<?= APP::Module('Routing')->root ?>tunnels/api/subscribe.json">
            email <input name="email" value="evildevel@ya.ru"><br>
            tunnel <input name="tunnel[0]" value="55"><input name="tunnel[1]" value="actions"><input name="tunnel[2]" value="565"><input name="tunnel[3]" value="0"><br>
            activation <input name="activation[0]" value="20"><input name="activation[1]" value="http://yandex.ru"><br>
            source <input name="source" value="test"><br>
            <input type="submit" value="Subscribe">
        </form>    
        <?
    }
    
    public function Subscribe($input) {
        $email = mb_strtolower(substr(trim($input['email']), 0, 250), APP::$conf['encoding']);
        
        if (!preg_match('/^([a-zа-яё0-9]+[_\-\.]?)*[a-zа-яё0-9]@([a-zа-яё0-9]+[-\.]?)*[a-zа-яё0-9]\.[a-zа-яё0-9]+$/iu', $email)) {
            return [
                'status' => 'error',
                'code' => 101
            ];
        }

        $user = APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'users', [['email', '=', $email, PDO::PARAM_STR]]
        ) ? APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            [
                'users.id', 
                'users.email', 
                'users.role', 
                'UNIX_TIMESTAMP(users.reg_date) AS reg_date',
                'users_about.value AS state'
            ], 
            'users',
            [
                ['users.email', '=', $email, PDO::PARAM_STR],
                ['users_about.item', '=', 'state', PDO::PARAM_STR]
            ],
            ['join/users_about' => [['users.id', '=', 'users_about.user']]],
            ['users.id']
        ) : [
            'id' => APP::Module('Users')->Register(
                $email, 
                APP::Module('Users')->GeneratePassword((int) APP::Module('Users')->settings['module_users_gen_pass_length'])
            ),
            'email' => $email,
            'role' => 'new',
            'reg_date' => time(),
            'state' => 'inactive'
        ];
        
        $source = isset($input['source']) ? substr($input['source'], 0, 100) : 'Subscribe';
        
        if (!APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'users_about', 
            [
                ['user', '=', $user['id'], PDO::PARAM_INT],
                ['item', '=', 'source', PDO::PARAM_STR]
            ]
        )) {
            APP::Module('DB')->Insert(
                APP::Module('Users')->settings['module_users_db_connection'], 'users_about',
                [
                    'id' => 'NULL',
                    'user' => [$user['id'], PDO::PARAM_INT],
                    'item' => ['source', PDO::PARAM_STR],
                    'value' => [$source, PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );
        }

        $tunnel = $input['tunnel'];

        if (isset($input['roles_tunnel'])) {
            if (isset($input['roles_tunnel'][$user['role']])) {
                $tunnel = $input['roles_tunnel'][$user['role']];
            }
        }
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels', 
            [
                ['id', '=', $tunnel[0], PDO::PARAM_INT],
                ['state', '=', 'active', PDO::PARAM_STR]
            ]
        )) {
            return [
                'status' => 'error',
                'code' => 201
            ];
        }
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels_' . $tunnel[1], 
            [
                ['id', '=', $tunnel[2], PDO::PARAM_INT]
            ]
        )) {
            return [
                'status' => 'error',
                'code' => 202
            ];
        }
        
        if ($tunnel[3] > $this->settings['module_tunnels_max_run_timeout']) {
            return [
                'status' => 'error',
                'code' => 203
            ];
        }
        
        $activation = $input['activation'];
        
        if (!APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'mail_letters', 
            [
                ['id', '=', $activation[0], PDO::PARAM_INT]
            ]
        )) {
            return [
                'status' => 'error',
                'code' => 301
            ];
        }
        
        if (!isset($activation[1])) {
            return [
                'status' => 'error',
                'code' => 302
            ];
        }
        
        $welcome = isset($input['welcome']) ? $input['welcome'] : false;
        
        if ($welcome) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'tunnels', 
                [
                    ['id', '=', $welcome[0], PDO::PARAM_INT],
                    ['state', '=', 'active', PDO::PARAM_STR]
                ]
            )) {
                return [
                    'status' => 'error',
                    'code' => 401
                ];
            }
            
            if (!APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'tunnels_' . $welcome[1], 
                [
                    ['id', '=', $welcome[2], PDO::PARAM_INT]
                ]
            )) {
                return [
                    'status' => 'error',
                    'code' => 402
                ];
            }
            
            if ($welcome[3] > $this->settings['module_tunnels_max_run_timeout']) {
                return [
                    'status' => 'error',
                    'code' => 403
                ];
            }
        }
        
        $queue_timeout = isset($input['queue_timeout']) ? (int) $input['queue_timeout'] : $this->settings['module_tunnels_default_queue_timeout'];
        
        if ($queue_timeout > $this->settings['module_tunnels_max_queue_timeout']) {
            return [
                'status' => 'error',
                'code' => 501
            ];
        }
        
        $complete_tunnels = isset($input['complete_tunnels']) ? $input['complete_tunnels'] : false;
        $pause_tunnels = isset($input['pause_tunnels']) ? $input['pause_tunnels'] : false;
        $input_data = isset($input['input_data']) ? json_encode($input['input_data']) : '{}';
        
        if (isset($input['about_user'])) {
            foreach ((array) $input['about_user'] as $item => $value) {
                if (!APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'users_about', 
                    [
                        ['user', '=', $user['id'], PDO::PARAM_INT],
                        ['item', '=', $item, PDO::PARAM_STR]
                    ]
                )) {
                    APP::Module('DB')->Insert(
                        APP::Module('Users')->settings['module_users_db_connection'], 'users_about',
                        [
                            'id' => 'NULL',
                            'user' => [$user['id'], PDO::PARAM_INT],
                            'item' => [$item, PDO::PARAM_STR],
                            'value' => [$value, PDO::PARAM_STR],
                            'up_date' => 'NOW()'
                        ]
                    );
                }
            }
        }
        
        if (isset($input['auto_save_about'])) {
            if ((bool) $input['auto_save_about']) {
                APP::Module('Users')->SaveAbout($user['id']);
            }
        }
        
        if (isset($input['save_utm'])) {
            if ((bool) $input['save_utm']) {
                APP::Module('Users')->SaveUTMLabels($user['id']);
            }
        }

        if ($welcome) {
            if ((((time() - $user['reg_date']) <= $this->settings['module_tunnels_indoctrination_lifetime']) && ($user['state'] == 'active') && (!APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'tunnels_users', 
                [
                    ['tunnel_id', '=', $welcome[0], PDO::PARAM_INT],
                    ['user_id', '=', $user['id'], PDO::PARAM_INT]
                ]
            )))) {
                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [APP::Module('DB')->Insert(
                            $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                            [
                                'id' => 'NULL',
                                'tunnel_id' => [$welcome[0], PDO::PARAM_INT],
                                'user_id' => [$user['id'], PDO::PARAM_INT],
                                'state' => ['active', PDO::PARAM_STR],
                                'resume_date' => [date('Y-m-d H:i:s', (time() + $welcome[3])), PDO::PARAM_STR],
                                'object' => [$welcome[1] . ':' . $welcome[2], PDO::PARAM_STR],
                                'input_data' => ['{}', PDO::PARAM_STR]
                            ]
                        ), PDO::PARAM_INT],
                        'label_id' => ['run', PDO::PARAM_STR],
                        'token' => '""',
                        'info' => [json_encode($input), PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );
            }
        }
        
        // Наличие целевого процесса у пользователя
        $target_tunnel_exist = (bool) APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels_users', 
            [
                ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                ['user_id', '=', $user['id'], PDO::PARAM_INT]
            ]
        );
        
        // Целевой процесс на паузе
        $pause_target_tunnel = (bool) APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels_users', 
            [
                ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                ['user_id', '=', $user['id'], PDO::PARAM_INT],
                ['state', '=', 'pause', PDO::PARAM_INT]
            ]
        );
        
        $activation_settings = [
            'expire' => strtotime('+' . APP::Module('Users')->settings['module_users_timeout_activation']),
            'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user['id']) . '/' . APP::Module('Crypt')->Encode(json_encode([
                'token' => 'tunnel',
                'return' => $activation[1],
                'tunnel' => $tunnel,
                'source' => $source,
                'welcome' => $welcome,
                'queue_timeout' => $queue_timeout,
                'complete_tunnels' => $complete_tunnels,
                'pause_tunnels' => $pause_tunnels,
                'input_data' => $input_data
            ]))
        ];
        
        switch (APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['type'], 'tunnels', [['id', '=', $tunnel[0], PDO::PARAM_INT]]
        )) {
            case 'static':
                // Наличие активных статических процессов у пользователя
                $active_static_tunnels = APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'tunnels_users', 
                    [
                        ['tunnel_id', 'IN', APP::Module('DB')->Select(
                            $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                            ['id'], 'tunnels', [['type', '=', 'static', PDO::PARAM_STR]]
                        )],
                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                        ['state', '=', 'active', PDO::PARAM_STR]
                    ]
                );
                
                // Целевой процесс в очереди
                $queue_target_tunnel = APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'tunnels_queue', 
                    [
                        ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                        ['user_id', '=', $user['id'], PDO::PARAM_INT]
                    ]
                );
                
                switch ($user['state']) {
                    case 'active':
                        
                        // Активный юзер не получает статические туннели, не проходил целевой туннель
                        if ((!$active_static_tunnels) && (!$target_tunnel_exist)) {
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$tunnel[0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $tunnel[3])), PDO::PARAM_STR],
                                    'object' => [$tunnel[1] . ':' . $tunnel[2], PDO::PARAM_STR],
                                    'input_data' => [$input_data, PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }

                            return [
                                'status' => 'success',
                                'user_tunnel_id' => $user_tunnel_id
                            ];
                        }

                        // Активный юзер не получает статические туннели, целевой туннель на паузе
                        if ((!$active_static_tunnels) && ($pause_target_tunnel)) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                'state' => 'active'
                            ], [
                                ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                            ]);

                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['resume', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => '""',
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('resume_tunnel', [
                                'id' => $user_tunnel_id
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }

                            return [
                                'status' => 'resume',
                                'user_tunnel_id' => $user_tunnel_id
                            ];
                        }

                        // Активный юзер, проходил/проходит целевой туннель, не на паузе
                        if ((!$pause_target_tunnel) && ($target_tunnel_exist)) {
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [APP::Module('DB')->Select(
                                        $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                        ['id'], 'tunnels_users', 
                                        [
                                            ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                                            ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                            ['state', '!=', 'pause', PDO::PARAM_STR]
                                        ]
                                    ), PDO::PARAM_INT],
                                    'label_id' => ['subscribe_attempt', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );

                            return [
                                'status' => 'exist'
                            ];
                        }

                        // Активный юзер получает статический туннель, не проходил целевой туннель, нет целевого туннеля в очереди
                        if ((($active_static_tunnels) && (!$target_tunnel_exist) && (!$queue_target_tunnel))) {
                            $tunnel_queue_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_queue',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$tunnel[0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'object_id' => [$tunnel[1] . ':' . $tunnel[2], PDO::PARAM_STR],
                                    'timeout' => [$queue_timeout, PDO::PARAM_INT],
                                    'settings' => [json_encode([
                                        'welcome' => $welcome,
                                        'complete_tunnels' => $complete_tunnels,
                                        'pause_tunnels' => $pause_tunnels,
                                        'input_data' => $input_data
                                    ]), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            ); 
                            
                            APP::Module('Triggers')->Exec('add_tunnel_queue', [
                                'id' => $tunnel_queue_id
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            return [
                                'status' => 'queue_success',
                                'queue_id' => $tunnel_queue_id
                            ];
                        }

                        // Активный юзер получает статический туннель, не проходил целевой туннель, есть целевой туннель в очереди.
                        if ((($active_static_tunnels) && (!$target_tunnel_exist) && ($queue_target_tunnel))) {
                            return [
                                'status' => 'queue_exist'
                            ];
                        }

                        // Активный юзер получает статический туннель, целевой туннель на паузе
                        if (($active_static_tunnels) && ($pause_target_tunnel)) {
                            return [
                                'status' => 'error',
                                'code' => 001
                            ];
                        }
                        
                        break;
                    case 'inactive':
                        
                        // Неактивный юзер, не проходил целевой туннель, нет целевого туннеля в очереди
                        if ((!$target_tunnel_exist) && (!$queue_target_tunnel)) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }

                        // Неактивный юзер, не проходил целевой туннель, есть целевой туннель в очереди
                        if ((!$target_tunnel_exist) && ($queue_target_tunnel)) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }

                        // Неактивный юзер, целевой туннель на паузе
                        if ($pause_target_tunnel) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }

                        // Неактивный юзер, прошел целевой туннель
                        if (($target_tunnel_exist) && (!$pause_target_tunnel)) {
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [APP::Module('DB')->Select(
                                        $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                        ['id'], 'tunnels_users', 
                                        [
                                            ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                                            ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                            ['state', '!=', 'pause', PDO::PARAM_STR]
                                        ]
                                    ), PDO::PARAM_INT],
                                    'label_id' => ['subscribe_attempt', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );

                            return [
                                'status' => 'exist'
                            ];
                        }
                        
                        break;
                    case 'blacklist':
                    case 'dropped':
                        return [
                            'status' => 'block'
                        ];
                }
                break;
            case 'dynamic':
                switch ($user['state']) {
                    case 'active':
                        
                        // Активный юзер, не проходил целевой туннель
                        if (!$target_tunnel_exist) {
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$tunnel[0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $tunnel[3])), PDO::PARAM_STR],
                                    'object' => [$tunnel[1] . ':' . $tunnel[2], PDO::PARAM_STR],
                                    'input_data' => [$input_data, PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }

                            return [
                                'status' => 'success',
                                'user_tunnel_id' => $user_tunnel_id
                            ];
                        }

                        // Активный юзер, целевой туннель на паузе
                        if ($pause_target_tunnel) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                'state' => 'active'
                            ], [
                                ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                            ]);

                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['resume', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => '""',
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('resume_tunnel', [
                                'id' => $user_tunnel_id
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }

                            return [
                                'status' => 'resume',
                                'user_tunnel_id' => $user_tunnel_id
                            ];
                        }

                        // Активный юзер, проходил/проходит целевой туннель, не на паузе
                        if ((!$pause_target_tunnel) && ($target_tunnel_exist)) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $tunnel[0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '!=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            if ($user_tunnel_id) {
                                APP::Module('DB')->Insert(
                                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                    [
                                        'id' => 'NULL',
                                        'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                        'label_id' => ['complete', PDO::PARAM_STR],
                                        'token' => '""',
                                        'info' => '""',
                                        'cr_date' => 'NOW()'
                                    ]
                                );

                                APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                    'state' => 'complete',
                                    'resume_date' => '0000-00-00 00:00:00',
                                    'object' => '',
                                    'input_data' => ''
                                ], [
                                    ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                                ]);

                                APP::Module('Triggers')->Exec('complete_tunnel', [
                                    'id' => $user_tunnel_id
                                ]);
                            }
                            
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$tunnel[0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $tunnel[3])), PDO::PARAM_STR],
                                    'object' => [$tunnel[1] . ':' . $tunnel[2], PDO::PARAM_STR],
                                    'input_data' => [$input_data, PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($complete_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $complete_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($pause_tunnels) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $pause_tunnels],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }

                            return [
                                'status' => 'success',
                                'user_tunnel_id' => $user_tunnel_id
                            ];
                        }
                        break;
                    case 'inactive':
                        
                        // Неактивный юзер, не проходил целевой туннель или целевой туннель на паузе
                        if ((!$target_tunnel_exist) || ($pause_target_tunnel)) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }
                        
                        // Неактивный юзер, не проходил целевой туннель
                        if (!$target_tunnel_exist) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }

                        // Неактивный юзер, целевой туннель на паузе
                        if ($pause_target_tunnel) {
                            APP::Module('Mail')->Send($user['email'], $activation[0], $activation_settings);
                            
                            if (!APP::Module('DB')->Select(
                                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['COUNT(id)'], 'task_manager',
                                [
                                    ['token', '=', $user['id'] . '_tunnel_activation', PDO::PARAM_STR],
                                    ['state', '=', 'wait', PDO::PARAM_STR]
                                ]
                            )) {
                                APP::Module('TaskManager')->Add(
                                    'Mail', 'Send', 
                                    date('Y-m-d H:i:s', (time() + $this->settings['module_tunnels_resend_acrivation_timeout'])), 
                                    json_encode($activation_settings), 
                                    $user['id'] . '_tunnel_activation', 'wait'
                                );
                            }

                            return [
                                'status' => 'activation'
                            ];
                        }
                        
                        break;
                    case 'blacklist':
                    case 'dropped':
                        return Array(
                            'status' => 'block'
                        );
                }
                break;
        }
        
    }
    
    public function Activate($action, $input) {
        if (isset($input['params'])) {
            if ($input['params']['token'] == 'tunnel') {
                $user = APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    [
                        'users.id', 
                        'users.email', 
                        'users.role', 
                        'UNIX_TIMESTAMP(users.reg_date) AS reg_date',
                        'users_about.value AS state'
                    ], 
                    'users',
                    [
                        ['users.id', '=', $input['user_id'], PDO::PARAM_INT],
                        ['users_about.item', '=', 'state', PDO::PARAM_STR]
                    ],
                    ['join/users_about' => [['users.id', '=', 'users_about.user']]],
                    ['users.id']
                );

                if ($input['params']['welcome']) {
                    if ((((time() - $user['reg_date']) <= $this->settings['module_tunnels_indoctrination_lifetime']) && ($user['state'] == 'active') && (!APP::Module('DB')->Select(
                        $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['COUNT(id)'], 'tunnels_users', 
                        [
                            ['tunnel_id', '=', $input['params']['welcome'][0], PDO::PARAM_INT],
                            ['user_id', '=', $user['id'], PDO::PARAM_INT]
                        ]
                    )))) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                            [
                                'id' => 'NULL',
                                'user_tunnel_id' => [APP::Module('DB')->Insert(
                                    $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                    [
                                        'id' => 'NULL',
                                        'tunnel_id' => [$input['params']['welcome'][0], PDO::PARAM_INT],
                                        'user_id' => [$user['id'], PDO::PARAM_INT],
                                        'state' => ['active', PDO::PARAM_STR],
                                        'resume_date' => [date('Y-m-d H:i:s', (time() + $input['params']['welcome'][3])), PDO::PARAM_STR],
                                        'object' => [$input['params']['welcome'][1] . ':' . $input['params']['welcome'][2], PDO::PARAM_STR],
                                        'input_data' => ['{}', PDO::PARAM_STR]
                                    ]
                                ), PDO::PARAM_INT],
                                'label_id' => ['run', PDO::PARAM_STR],
                                'token' => '""',
                                'info' => [json_encode($input), PDO::PARAM_STR],
                                'cr_date' => 'NOW()'
                            ]
                        );
                    }
                }
                
                // Наличие целевого процесса у пользователя
                $target_tunnel_exist = (bool) APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'tunnels_users', 
                    [
                        ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                        ['user_id', '=', $user['id'], PDO::PARAM_INT]
                    ]
                );

                // Целевой процесс на паузе
                $pause_target_tunnel = (bool) APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'tunnels_users', 
                    [
                        ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                        ['state', '=', 'pause', PDO::PARAM_INT]
                    ]
                );

                switch (APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['type'], 'tunnels', [['id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT]]
                )) {
                    case 'static':

                        // Наличие активных статических процессов у пользователя
                        $active_static_tunnels = APP::Module('DB')->Select(
                            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                            ['COUNT(id)'], 'tunnels_users', 
                            [
                                ['tunnel_id', 'IN', APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels', [['type', '=', 'static', PDO::PARAM_STR]]
                                )],
                                ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                ['state', '=', 'active', PDO::PARAM_STR]
                            ]
                        );

                        // Целевой процесс в очереди
                        $queue_target_tunnel = APP::Module('DB')->Select(
                            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                            ['COUNT(id)'], 'tunnels_queue', 
                            [
                                ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                                ['user_id', '=', $user['id'], PDO::PARAM_INT]
                            ]
                        );

                        // Не получает статические туннели, не проходил целевой туннель
                        if ((!$active_static_tunnels) && (!$target_tunnel_exist)) {
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$input['params']['tunnel'][0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $input['params']['tunnel'][3])), PDO::PARAM_STR],
                                    'object' => [$input['params']['tunnel'][1] . ':' . $input['params']['tunnel'][2], PDO::PARAM_STR],
                                    'input_data' => [$input['params']['input_data'], PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        // Не получает статические туннели, целевой туннель на паузе
                        if ((!$active_static_tunnels) && ($pause_target_tunnel)) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                'state' => 'active'
                            ], [
                                ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                            ]);

                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['resume', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => '""',
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('resume_tunnel', [
                                'id' => $user_tunnel_id
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        // Получает статический туннель, не проходил целевой туннель, нет целевого туннеля в очереди
                        if ((($active_static_tunnels) && (!$target_tunnel_exist) && (!$queue_target_tunnel))) {
                            $tunnel_queue_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_queue',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$input['params']['tunnel'][0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'object_id' => [$input['params']['tunnel'][1] . ':' . $input['params']['tunnel'][2], PDO::PARAM_STR],
                                    'timeout' => [$input['params']['queue_timeout'], PDO::PARAM_INT],
                                    'settings' => [json_encode([
                                        'welcome' => $input['params']['welcome'],
                                        'complete_tunnels' => $input['params']['complete_tunnels'],
                                        'pause_tunnels' => $input['params']['pause_tunnels'],
                                        'input_data' => $input['params']['input_data']
                                    ]), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            ); 
                            
                            APP::Module('Triggers')->Exec('add_tunnel_queue', [
                                'id' => $tunnel_queue_id
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        // Получает статический туннель, целевой туннель на паузе
                        if (($active_static_tunnels) && ($pause_target_tunnel)) {
                            
                        }

                        break;
                    case 'dynamic':

                        // Не проходил целевой туннель
                        if (!$target_tunnel_exist) {
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$input['params']['tunnel'][0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $input['params']['tunnel'][3])), PDO::PARAM_STR],
                                    'object' => [$input['params']['tunnel'][1] . ':' . $input['params']['tunnel'][2], PDO::PARAM_STR],
                                    'input_data' => [$input['params']['input_data'], PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        // Целевой туннель на паузе
                        if ($pause_target_tunnel) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                'state' => 'active'
                            ], [
                                ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                            ]);

                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['resume', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => '""',
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('resume_tunnel', [
                                'id' => $user_tunnel_id
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        // Проходил/проходит целевой туннель, не на паузе
                        if ((!$pause_target_tunnel) && ($target_tunnel_exist)) {
                            $user_tunnel_id = APP::Module('DB')->Select(
                                $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                ['id'], 'tunnels_users', 
                                [
                                    ['tunnel_id', '=', $input['params']['tunnel'][0], PDO::PARAM_INT],
                                    ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                    ['state', '!=', 'pause', PDO::PARAM_STR]
                                ]
                            );
                            
                            if ($user_tunnel_id) {
                                APP::Module('DB')->Insert(
                                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                    [
                                        'id' => 'NULL',
                                        'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                        'label_id' => ['complete', PDO::PARAM_STR],
                                        'token' => '""',
                                        'info' => '""',
                                        'cr_date' => 'NOW()'
                                    ]
                                );

                                APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                    'state' => 'complete',
                                    'resume_date' => '0000-00-00 00:00:00',
                                    'object' => '',
                                    'input_data' => ''
                                ], [
                                    ['id', '=', $user_tunnel_id, PDO::PARAM_INT]
                                ]);

                                APP::Module('Triggers')->Exec('complete_tunnel', [
                                    'id' => $user_tunnel_id
                                ]);
                            }
                            
                            $user_tunnel_id = APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                [
                                    'id' => 'NULL',
                                    'tunnel_id' => [$input['params']['tunnel'][0], PDO::PARAM_INT],
                                    'user_id' => [$user['id'], PDO::PARAM_INT],
                                    'state' => ['active', PDO::PARAM_STR],
                                    'resume_date' => [date('Y-m-d H:i:s', (time() + $input['params']['tunnel'][3])), PDO::PARAM_STR],
                                    'object' => [$input['params']['tunnel'][1] . ':' . $input['params']['tunnel'][2], PDO::PARAM_STR],
                                    'input_data' => [$input['params']['input_data'], PDO::PARAM_STR]
                                ]
                            );
                            
                            APP::Module('DB')->Insert(
                                $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                [
                                    'id' => 'NULL',
                                    'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                                    'label_id' => ['subscribe', PDO::PARAM_STR],
                                    'token' => '""',
                                    'info' => [json_encode($input), PDO::PARAM_STR],
                                    'cr_date' => 'NOW()'
                                ]
                            );
                            
                            APP::Module('Triggers')->Exec('subscribe_tunnel', [
                                'id' => $user_tunnel_id,
                                'input' => $input
                            ]);
                            
                            if ($input['params']['complete_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['complete_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['complete', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'complete',
                                        'resume_date' => '0000-00-00 00:00:00',
                                        'object' => '',
                                        'input_data' => ''
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('complete_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                            
                            if ($input['params']['pause_tunnels']) {
                                foreach (APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                                    ['id'], 'tunnels_users', 
                                    [
                                        ['tunnel_id', 'IN', $input['params']['pause_tunnels']],
                                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                                        ['state', '=', 'active', PDO::PARAM_STR]
                                    ]
                                ) as $id) {
                                    APP::Module('DB')->Insert(
                                        $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                                        [
                                            'id' => 'NULL',
                                            'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                            'label_id' => ['pause', PDO::PARAM_STR],
                                            'token' => '""',
                                            'info' => '""',
                                            'cr_date' => 'NOW()'
                                        ]
                                    );
                                    
                                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                                        'state' => 'pause'
                                    ], [
                                        ['id', '=', $id, PDO::PARAM_INT]
                                    ]);
                                    
                                    APP::Module('Triggers')->Exec('pause_tunnel', [
                                        'id' => $id
                                    ]);
                                }
                            }
                        }

                        break;
                }
            }
        }
        
        return $input;
    }
    
    
    public function Exec() {
        $lock = fopen($this->settings['module_tunnels_tmp_dir'] . '/module_tunnels_exec.lock', 'w');
        
        if (flock($lock, LOCK_EX|LOCK_NB)) { 
            set_time_limit($this->settings['module_tunnels_max_execution_time']);
            ini_set('memory_limit', $this->settings['module_tunnels_memory_limit']);

            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'factors'], 'tunnels',
                [['state', '=', 'active', PDO::PARAM_STR]]
            ) as $tunnel) {
                $this->t_factors[$tunnel['id']] = json_decode($tunnel['factors'], true);
            }

            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'action', 'settings', 'child_object'], 'tunnels_actions'
            ) as $action) {
                $action['settings'] = json_decode($action['settings'], true);
                $action['child_object'] = $action['child_object'] ? explode(':', $action['child_object']) : false;
                $this->t_actions[$action['id']] = $action;
            }
            
            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'rules', 'child_object_y', 'child_object_n'], 'tunnels_conditions'
            ) as $condition) {
                $condition['rules'] = json_decode($condition['rules'], true);
                $condition['child_object_y'] = $condition['child_object_y'] ? explode(':', $condition['child_object_y']) : false;
                $condition['child_object_n'] = $condition['child_object_n'] ? explode(':', $condition['child_object_n']) : false;
                $this->t_conditions[$condition['id']] = $condition;
            }
            
            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'timeout', 'timeout_type', 'child_object'], 'tunnels_timeouts'
            ) as $timeout) {
                $this->t_timeouts[$timeout['id']] = $timeout;
            }

            $c_tunnels = 0;

            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                [
                    'tunnels_users.id',
                    'tunnels_users.tunnel_id',
                    'tunnels_users.user_id',
                    'tunnels_users.object',
                    'tunnels_users.input_data'
                ], 
                'tunnels_users',
                [
                    ['tunnels_users.tunnel_id', 'IN', array_keys($this->t_factors)],
                    ['tunnels_users.state', '=', 'active', PDO::PARAM_STR],
                    ['UNIX_TIMESTAMP(tunnels_users.resume_date)', '<=', time(), PDO::PARAM_INT],
                    ['tunnels_users.object', '!=', '', PDO::PARAM_STR],
                    ['users.role', 'IN', APP::Module('DB')->Select(
                        APP::Module('Registry')->conf['connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                        ['value'], 'registry',
                        [
                            ['item', '=', 'module_users_role', PDO::PARAM_STR],
                            ['id', 'IN', explode(',', $this->settings['module_tunnels_active_user_roles'])]
                        ]
                    )],
                ],
                ['join/users' => [['users.id', '=', 'tunnels_users.user_id']]],
                ['tunnels_users.id'],
                false,
                ['tunnels_users.id', 'ASC'],
                [0, $this->settings['module_tunnels_execution_tunnels_number']]
            ) as $tunnel) {
                $object = explode(':', $tunnel['object']);
                $method = 'Exec_' . $object[0];
                $property = 't_' . $object[0];
                $this->{$method}($this->{$property}[$object[1]], $tunnel);
                ++ $c_tunnels;
            }
            
            $runtime = microtime(true) - RUNTIME;
            
            APP::Module('DB')->Insert(
                $this->settings['module_tunnels_db_connection'], 'tunnels_runtime_log',
                [
                    'id' => 'NULL',
                    'runtime' => [$runtime, PDO::PARAM_STR],
                    'tunnels' => [$c_tunnels, PDO::PARAM_INT],
                    'cr_date' => 'NOW()'
                ]
            );
        } else { 
            exit;
        } 
        
        fclose($lock);
    }
    
    private function Exec_actions($object, $tunnel) {
        switch ($object['action']) {
            case 'send_mail':
                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [$tunnel['id'], PDO::PARAM_INT],
                        'label_id' => ['sendmail', PDO::PARAM_STR],
                        'token' => [$object['settings']['letter'], PDO::PARAM_STR],
                        'info' => [json_encode([
                            'settings' => $object['settings'],
                            'result' => APP::Module('Mail')->Send(
                                APP::Module('DB')->Select(
                                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                                    ['email'], 'users',
                                    [['id', '=', $tunnel['user_id'], PDO::PARAM_INT]]
                                ),
                                $object['settings']['letter'],
                                [
                                    // Available in a letter like $data['param']
                                    'user_tunnel_id' => $tunnel['id'],
                                    'tunnel_id' => $tunnel['tunnel_id']
                                ]
                            )
                        ]), PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );
                break;
            case 'auto_complete':
                if ($user_tunnel_id = APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['id'], 'tunnels_users',
                    [
                        ['tunnel_id', '=', $object['settings']['tunnel_id'], PDO::PARAM_INT],
                        ['user_id', '=', $tunnel['user_id'], PDO::PARAM_INT]
                    ]
                )) {
                    APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                        'state' => 'complete',
                        'resume_date' => '0000-00-00 00:00:00',
                        'object' => '',
                        'input_data' => ''
                    ], [
                        ['id', '=', $user_tunnel_id, PDO::PARAM_INT],
                        ['state', '!=', 'complete', PDO::PARAM_STR]
                    ]);
                } else {
                    $user_tunnel_id = APP::Module('DB')->Insert(
                        $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                        [
                            'id' => 'NULL',
                            'tunnel_id' => [$object['settings']['tunnel_id'], PDO::PARAM_INT],
                            'user_id' => [$tunnel['user_id'], PDO::PARAM_INT],
                            'state' => ['complete', PDO::PARAM_STR],
                            'resume_date' => ['0000-00-00 00:00:00', PDO::PARAM_STR],
                            'object' => '""',
                            'input_data' => ''
                        ]
                    );
                }
                
                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                        'label_id' => ['auto_complete', PDO::PARAM_STR],
                        'token' => '""',
                        'info' => [json_encode([
                            'mode' => 'child'
                        ]), PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );
                
                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                        'label_id' => ['auto_complete', PDO::PARAM_STR],
                        'token' => '""',
                        'info' => [json_encode([
                            'mode' => 'parent', 
                            'user_tunnel_id' => $user_tunnel_id, 
                            'settings' => $object['settings']
                        ]), PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );
                break;
            case 'complete':
                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [$tunnel['id'], PDO::PARAM_INT],
                        'label_id' => ['complete', PDO::PARAM_STR],
                        'token' => '""',
                        'info' => '""',
                        'cr_date' => 'NOW()'
                    ]
                );

                APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                    'state' => 'complete',
                    'resume_date' => '0000-00-00 00:00:00',
                    'object' => '',
                    'input_data' => ''
                ], [
                    ['id', '=', $tunnel['id'], PDO::PARAM_INT]
                ]);
                break;
        }
        
        if ($object['child_object']) {
            $method = 'Exec_' . $object['child_object'][0];
            $property = 't_' . $object['child_object'][0];
            $this->{$method}($this->{$property}[$object['child_object'][1]], $tunnel);
        }
    }
    
    private function Exec_conditions($object, $tunnel) {
        $cache_id = md5(json_encode($object['rules']));
        
        if (!$users = APP::Module('Cache')->memcache->get($cache_id)) {
            $users = $this->Search($object['rules']);
            APP::Module('Cache')->memcache->set($cache_id, $users);
	}
        
        $result = array_search($tunnel['user_id'], $users) === false ? 'n' : 'y';
        $child_object = $object['child_object_' . $result];
        
        APP::Module('DB')->Insert(
            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
            [
                'id' => 'NULL',
                'user_tunnel_id' => [$tunnel['id'], PDO::PARAM_INT],
                'label_id' => ['condition', PDO::PARAM_STR],
                'token' => '""',
                'info' => [$object['id'] . ':' . $result, PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        if ($child_object) {
            $method = 'Exec_' . $child_object[0];
            $property = 't_' . $child_object[0];
            $this->{$method}($this->{$property}[$child_object[1]], $tunnel);
        }
    }
    
    private function Exec_timeouts($object, $tunnel) {
        $timeout = 0;
   
        switch ($object['timeout_type']) {
            case 'min':     $timeout = (int) $object['timeout'] * 60;       break;
            case 'hours':   $timeout = (int) $object['timeout'] * 3600;     break;
            case 'days':    $timeout = (int) $object['timeout'] * 86400;    break;
            default:        $timeout = (int) $object['timeout'];
        }
        
        /*
        $factors = Array();
        
        foreach (Shell::$app->Get('extensions','EProcesses')->factors[$process['process_id']] as $factor) {
            switch ($factor['method']) {
                case 'open_letters_pct_30':
                    if (!array_key_exists('open_letters_pct_30', $factors)) {
                        $factors['open_letters_pct_30'] = Shell::$app->Get('extensions','EORM')->SelectV2(
                            'pult_mailing',
                            Array(
                                'fetch', 
                                PDO::FETCH_COLUMN
                            ),
                            Array(
                                'pct'
                            ), 
                            'open_letters_pct_30',
                            Array(
                                Array('user_id', '=', $user['id'])
                            )
                        );
                    }
                    
                    if (($factors['open_letters_pct_30'] >= $factor['settings']['from']) && ($factors['open_letters_pct_30'] <= $factor['settings']['to'])) {
                        $timeout = $timeout * $factor['settings']['factor'];
                    }
                    break;
            }
        }
         */

        $less_factor = (int) APP::Module('DB')->Select(
            $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['factor'], 'tunnels_timeouts_less_factors',
            [['user_id', '=', $tunnel['user_id'], PDO::PARAM_INT]]
        );

        if ($less_factor) {
            $timeout = $timeout * $less_factor;
        }
        
        APP::Module('DB')->Insert(
            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
            [
                'id' => 'NULL',
                'user_tunnel_id' => [$tunnel['id'], PDO::PARAM_INT],
                'label_id' => ['timeout', PDO::PARAM_STR],
                'token' => [$timeout, PDO::PARAM_STR],
                'info' => [json_encode($object), PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );

        if ($object['child_object']) {
            APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                'resume_date' => date('Y-m-d H:i:s', (time() + $timeout)),
                'object' => $object['child_object']
            ], [
                ['id', '=', $tunnel['id'], PDO::PARAM_INT]
            ]);
        }
    }
    
    
    public function Queue() {
        $lock = fopen($this->settings['module_tunnels_tmp_dir'] . '/module_tunnels_queue.lock', 'w');
        
        if (flock($lock, LOCK_EX|LOCK_NB)) { 
            set_time_limit($this->settings['module_tunnels_max_execution_time']);
            ini_set('memory_limit', $this->settings['module_tunnels_memory_limit']);
            
            $active_static_tunnels = APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                ['id'], 'tunnels', 
                [
                    ['type', '=', 'static', PDO::PARAM_STR],
                    ['state', '=', 'active', PDO::PARAM_STR]
                ]
            );
            
            $c_queues = 0;
            
            foreach (APP::Module('DB')->Select(
                $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                [
                    'id',
                    'tunnel_id',
                    'user_id',
                    'object_id',
                    'timeout',
                    'settings'
                ], 
                'tunnels_queue',
                [
                    ['tunnel_id', 'IN', $active_static_tunnels]
                ]
            ) as $tunnel) {
                $settings = json_decode($tunnel['settings'], true);
                
                $user = APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    [
                        'users.id', 
                        'users.email', 
                        'users.role', 
                        'UNIX_TIMESTAMP(users.reg_date) AS reg_date',
                        'users_about.value AS state'
                    ], 
                    'users',
                    [
                        ['users.id', '=', $tunnel['user_id'], PDO::PARAM_INT],
                        ['users_about.item', '=', 'state', PDO::PARAM_STR]
                    ],
                    ['join/users_about' => [['users.id', '=', 'users_about.user']]],
                    ['users.id']
                );
                
                if ($user['state'] != 'active') {
                    continue;
                }
                
                if (APP::Module('DB')->Select(
                    $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'tunnels_users', 
                    [
                        ['tunnel_id', 'IN', $active_static_tunnels],
                        ['user_id', '=', $user['id'], PDO::PARAM_INT],
                        ['state', '=', 'active', PDO::PARAM_STR]
                    ]
                )) {
                    continue;
                }
                
                if ($settings['welcome']) {
                    if ((((time() - $user['reg_date']) <= $this->settings['module_tunnels_indoctrination_lifetime']) && ($user['state'] == 'active') && (!APP::Module('DB')->Select(
                        $this->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['COUNT(id)'], 'tunnels_users', 
                        [
                            ['tunnel_id', '=', $settings['welcome'][0], PDO::PARAM_INT],
                            ['user_id', '=', $user['id'], PDO::PARAM_INT]
                        ]
                    )))) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                            [
                                'id' => 'NULL',
                                'user_tunnel_id' => [APP::Module('DB')->Insert(
                                    $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                                    [
                                        'id' => 'NULL',
                                        'tunnel_id' => [$settings['welcome'][0], PDO::PARAM_INT],
                                        'user_id' => [$user['id'], PDO::PARAM_INT],
                                        'state' => ['active', PDO::PARAM_STR],
                                        'resume_date' => [date('Y-m-d H:i:s', (time() + $settings['welcome'][3])), PDO::PARAM_STR],
                                        'object' => [$settings['welcome'][1] . ':' . $settings['welcome'][2], PDO::PARAM_STR],
                                        'input_data' => ['{}', PDO::PARAM_STR]
                                    ]
                                ), PDO::PARAM_INT],
                                'label_id' => ['run', PDO::PARAM_STR],
                                'token' => '""',
                                'info' => [json_encode($tunnel), PDO::PARAM_STR],
                                'cr_date' => 'NOW()'
                            ]
                        );
                    }
                }
                
                $user_tunnel_id = APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_users',
                    [
                        'id' => 'NULL',
                        'tunnel_id' => [$tunnel['tunnel_id'], PDO::PARAM_INT],
                        'user_id' => [$user['id'], PDO::PARAM_INT],
                        'state' => ['active', PDO::PARAM_STR],
                        'resume_date' => [date('Y-m-d H:i:s', (time() + $tunnel['timeout'])), PDO::PARAM_STR],
                        'object' => [$tunnel['object_id'], PDO::PARAM_STR],
                        'input_data' => [$settings['input_data'], PDO::PARAM_STR]
                    ]
                );

                APP::Module('DB')->Insert(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                    [
                        'id' => 'NULL',
                        'user_tunnel_id' => [$user_tunnel_id, PDO::PARAM_INT],
                        'label_id' => ['subscribe', PDO::PARAM_STR],
                        'token' => '""',
                        'info' => [json_encode($tunnel), PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );

                APP::Module('Triggers')->Exec('subscribe_tunnel', [
                    'id' => $user_tunnel_id,
                    'input' => $tunnel
                ]);

                if ($settings['complete_tunnels']) {
                    foreach (APP::Module('DB')->Select(
                        $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                        ['id'], 'tunnels_users', 
                        [
                            ['tunnel_id', 'IN', $settings['complete_tunnels']],
                            ['user_id', '=', $user['id'], PDO::PARAM_INT],
                            ['state', '=', 'active', PDO::PARAM_STR]
                        ]
                    ) as $id) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                            [
                                'id' => 'NULL',
                                'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                'label_id' => ['complete', PDO::PARAM_STR],
                                'token' => '""',
                                'info' => '""',
                                'cr_date' => 'NOW()'
                            ]
                        );

                        APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                            'state' => 'complete',
                            'resume_date' => '0000-00-00 00:00:00',
                            'object' => '',
                            'input_data' => ''
                        ], [
                            ['id', '=', $id, PDO::PARAM_INT]
                        ]);

                        APP::Module('Triggers')->Exec('complete_tunnel', [
                            'id' => $id
                        ]);
                    }
                }

                if ($settings['pause_tunnels']) {
                    foreach (APP::Module('DB')->Select(
                        $this->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                        ['id'], 'tunnels_users', 
                        [
                            ['tunnel_id', 'IN', $settings['pause_tunnels']],
                            ['user_id', '=', $user['id'], PDO::PARAM_INT],
                            ['state', '=', 'active', PDO::PARAM_STR]
                        ]
                    ) as $id) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_tunnels_db_connection'], 'tunnels_tags',
                            [
                                'id' => 'NULL',
                                'user_tunnel_id' => [$id, PDO::PARAM_INT],
                                'label_id' => ['pause', PDO::PARAM_STR],
                                'token' => '""',
                                'info' => '""',
                                'cr_date' => 'NOW()'
                            ]
                        );

                        APP::Module('DB')->Update($this->settings['module_tunnels_db_connection'], 'tunnels_users', [
                            'state' => 'pause'
                        ], [
                            ['id', '=', $id, PDO::PARAM_INT]
                        ]);

                        APP::Module('Triggers')->Exec('pause_tunnel', [
                            'id' => $id
                        ]);
                    }
                }
                
                APP::Module('DB')->Delete(
                    $this->settings['module_tunnels_db_connection'], 'tunnels_queue',
                    [['id', '=', $tunnel['id'], PDO::PARAM_INT]]
                );
                
                ++ $c_queues;
            }
            
            $runtime = microtime(true) - RUNTIME;
            
            APP::Module('DB')->Insert(
                $this->settings['module_tunnels_db_connection'], 'tunnels_queue_runtime_log',
                [
                    'id' => 'NULL',
                    'runtime' => [$runtime, PDO::PARAM_STR],
                    'queues' => [$c_queues, PDO::PARAM_INT],
                    'cr_date' => 'NOW()'
                ]
            );
        } else { 
            exit;
        } 
        
        fclose($lock);
    }

}

class TunnelsSearch {

    public function name($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'tunnels',
            [['name', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }

}

class TunnelsActions {

    public function remove($id, $settings) {
        return APP::Module('DB')->Delete(APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 'tunnels', [['id', 'IN', $id]]);
    }
    
}