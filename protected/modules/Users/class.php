<?
class Users {

    public $settings;
    private $users_search;
    private $users_actions;
    public $user = [];
    public $about = [
        'username',
        'state',
        'mobile_phone',
        'twitter',
        'skype'
    ];

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_users_register_activation_letter',
            'module_users_subscribe_activation_letter',
            'module_users_reset_password_letter',
            'module_users_change_password_letter',
            'module_users_subscription_restore_letter',
            'module_users_register_letter',
            'module_users_role',
            'module_users_rule',
            'module_users_db_connection',
            'module_users_ssh_connection',
            'module_users_auth_token',
            'module_users_check_rules',
            'module_users_min_pass_length',
            'module_users_gen_pass_length',
            'module_users_login_service',
            'module_users_change_password_service',
            'module_users_register_service',
            'module_users_reset_password_service',
            'module_users_oauth_client_fb_id',
            'module_users_oauth_client_fb_key',
            'module_users_oauth_client_google_id',
            'module_users_oauth_client_google_key',
            'module_users_oauth_client_vk_id',
            'module_users_oauth_client_vk_key',
            'module_users_oauth_client_ya_id',
            'module_users_oauth_client_ya_key',
            'module_users_timeout_activation',
            'module_users_timeout_email',
            'module_users_timeout_token',
            'module_users_tmp_dir',
            'module_users_profile_picture'
        ]);
        
        $this->users_search = new UsersSearch();
        $this->users_actions = new UsersActions();

        $this->user = &APP::Module('Sessions')->session['modules']['users']['user'];

        if (!isset(APP::Module('Sessions')->session['modules']['users']['double_auth'])) {
            APP::Module('Sessions')->session['modules']['users']['double_auth'] = false;
        }

        if (!$this->user) {
            $this->user = [
                'id' => 0,
                'role' => 'default'
            ];
        }

        if (isset($_COOKIE['modules']['users']['token'])) {
            if ($user = $this->Login($_COOKIE['modules']['users']['email'], $_COOKIE['modules']['users']['token'])) {
                $this->user = $this->Auth($user);
            }
        }

        if (((int) $this->settings['module_users_auth_token']) && ((int) $this->settings['module_users_login_service'])) {
            if (isset(APP::Module('Routing')->get['user_token'])) {
                $token = json_decode(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_token']), 1);

                if ($user = $this->Login($token[0], $token[1])) {
                    $this->user = $this->Auth($user, true, true);
                }
            }
        }

        if ($this->user['id']) {
            APP::Module('DB')->Update(
                $this->settings['module_users_db_connection'], 'users',
                ['last_visit' => date('Y-m-d H:i:s')],
                [['id', '=', $this->user['id'], PDO::PARAM_INT]]
            );
        }

        if ((int) $this->settings['module_users_check_rules']) {
            if ($target_uri = $this->CheckRules()) {
                $url = parse_url(APP::Module('Routing')->root . $target_uri);
                $target_query = ['return' => APP::Module('Crypt')->SafeB64Encode(APP::Module('Routing')->SelfUrl())];

                if (isset($url['query'])) {
                    foreach (explode('&', $url['query']) as $param) {
                        $param_data = explode('=', $param);
                        $target_query[$param_data[0]] = $param_data[1];
                    }
                }

                header('Location: ' . APP::Module('Routing')->root . $target_uri . '?' . http_build_query($target_query));
                exit;
            }
        }
    }
    
    public function UsersSearch($rules) {
        $out = Array();

        foreach ((array) $rules['rules'] as $rule) {
            $out[] = array_flip((array) $this->users_search->{$rule['method']}($rule['settings']));
        }
        
        if (array_key_exists('children', (array) $rules)) {
            $out[] = array_flip((array) $this->UsersSearch($rules['children']));
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

    public function Admin() {
        return APP::Render('users/admin/nav', 'content');
    }

    public function Dashboard() {
        return APP::Render('users/admin/dashboard/index', 'return');
    }
    
    public function APIDashboardAll() {
        $roles = [];

        foreach (APP::Module('Registry')->Get(['module_users_role'], ['value'])['module_users_role'] as $role) {
            $roles[$role['value']] = APP::Module('DB')->Select(
                $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['count(id)'], 'users', 
                [['role', '=', $role['value'], PDO::PARAM_STR]]
            );
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'roles' => $roles,
            'states' => [
                'active' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['count(distinct user)'], 'users_about', 
                    [
                        ['item', '=', 'state', PDO::PARAM_STR],
                        ['value', '=', 'active', PDO::PARAM_STR]
                    ]
                ),
                'inactive' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['count(distinct user)'], 'users_about', 
                    [
                        ['item', '=', 'state', PDO::PARAM_STR],
                        ['value', '=', 'inactive', PDO::PARAM_STR]
                    ]
                ),
                'blacklist' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['count(distinct user)'], 'users_about', 
                    [
                        ['item', '=', 'state', PDO::PARAM_STR],
                        ['value', '=', 'blacklist', PDO::PARAM_STR]
                    ]
                ),
                'dropped' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['count(distinct user)'], 'users_about', 
                    [
                        ['item', '=', 'state', PDO::PARAM_STR],
                        ['value', '=', 'dropped', PDO::PARAM_STR]
                    ]
                )
            ]
        ]);
        exit;
    }
    
    public function APIDashboardNew() {
        $range = [];

        for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
            $range[date('d-m-Y', $x)] = [
                'total'       => 0,
                'active'      => 0,
                'wait'        => 0,
                'unsubscribe' => 0,
                'dropped'     => 0
            ];
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['users_about.value as state', 'UNIX_TIMESTAMP(reg_date) AS cr_date'], 'users', [
            ['users_about.item', '=', 'state', PDO::PARAM_STR],
            ['users.reg_date', 'BETWEEN', '"' . date('Y-m-d', $_POST['date']['from']) . ' 00:00:00" AND "' . date('Y-m-d', $_POST['date']['to']) . ' 23:59:59"', PDO::PARAM_STR]
            ], [
                'left join/users_about' => [
                    ['users_about.user', '=', 'users.id']
                ]
            ], false, false, ['users.id', 'desc']
        ) as $user) {
            $date_index = date('d-m-Y', $user['cr_date']);

            if (!isset($range[$date_index])) {
                $range[$date_index] = [
                    'total'       => 0,
                    'active'      => 0,
                    'wait'        => 0,
                    'unsubscribe' => 0,
                    'dropped'     => 0
                ];
            }

            ++$range[$date_index]['total'];

            if ($user['state'] == 'active')
                ++$range[$date_index]['active'];
            if ($user['state'] == 'wait')
                ++$range[$date_index]['wait'];
            if ($user['state'] == 'unsubscribe')
                ++$range[$date_index]['unsubscribe'];
            if ($user['state'] == 'dropped')
                ++$range[$date_index]['dropped'];
        }

        $range_values = [];
        $out          = [];

        foreach ($range as $date_index => $counters) {
            $date_values = explode('-', $date_index);

            $range_values[] = [
                'dt'          => $date_index,
                'total'       => (int) $counters['total'],
                'active'      => Array((int) $counters['active'], APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"active"}},{"method":"user_cr_date","settings":{"from":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '","to":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '"}}]}')),
                'wait'        => Array((int) $counters['wait'], APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"wait"}},{"method":"user_cr_date","settings":{"from":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '","to":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '"}}]}')),
                'unsubscribe' => Array((int) $counters['unsubscribe'], APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"unsubscribe"}},{"method":"user_cr_date","settings":{"from":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '","to":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '"}}]}')),
                'dropped'     => Array((int) $counters['dropped'], APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"dropped"}},{"method":"user_cr_date","settings":{"from":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '","to":"' . $date_values[2] . '.' . $date_values[1] . '.' . $date_values[0] . '"}}]}')),
            ];

            $out['wait'][$date_index]        = [strtotime($date_index) * 1000, $counters['wait']];
            $out['unsubscribe'][$date_index] = [strtotime($date_index) * 1000, $counters['unsubscribe']];
            $out['dropped'][$date_index]     = [strtotime($date_index) * 1000, $counters['dropped']];
        }

        $date_from_values = explode('-', date('Y-m-d', $_POST['date']['from']));
        $date_to_values   = explode('-', date('Y-m-d', $_POST['date']['to']));

        $out = [
            'total'  => [
                'value' => (int) APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], ['count(id)'], 'users', [
                        ['reg_date', 'BETWEEN', '"' . date('Y-m-d', $_POST['date']['from']) . ' 00:00:00" AND "' . date('Y-m-d', $_POST['date']['to']) . ' 23:59:59"', PDO::PARAM_STR]
                    ]
                ),
                'hash'  => [
                    'active'      => APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"active"}},{"method":"user_cr_date","settings":{"from":"' . $date_from_values[2] . '.' . $date_from_values[1] . '.' . $date_from_values[0] . '","to":"' . $date_to_values[2] . '.' . $date_to_values[1] . '.' . $date_to_values[0] . '"}}]}'),
                    'wait'        => APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"wait"}},{"method":"user_cr_date","settings":{"from":"' . $date_from_values[2] . '.' . $date_from_values[1] . '.' . $date_from_values[0] . '","to":"' . $date_to_values[2] . '.' . $date_to_values[1] . '.' . $date_to_values[0] . '"}}]}'),
                    'unsubscribe' => APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"unsubscribe"}},{"method":"user_cr_date","settings":{"from":"' . $date_from_values[2] . '.' . $date_from_values[1] . '.' . $date_from_values[0] . '","to":"' . $date_to_values[2] . '.' . $date_to_values[1] . '.' . $date_to_values[0] . '"}}]}'),
                    'dropped'     => APP::Module('Crypt')->Encode('{"logic":"intersect","rules":[{"method":"user_state","settings":{"value":"dropped"}},{"method":"user_cr_date","settings":{"from":"' . $date_from_values[2] . '.' . $date_from_values[1] . '.' . $date_from_values[0] . '","to":"' . $date_to_values[2] . '.' . $date_to_values[1] . '.' . $date_to_values[0] . '"}}]}'),
                ]
            ],
            'values' => $range_values,
            'range'  => [
                'wait'        => array_values($out['wait']),
                'unsubscribe' => array_values($out['unsubscribe']),
                'dropped'     => array_values($out['dropped'])
            ]
        ];

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }
    
    public function Login($email, $password) {
        return (int) APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users',
            [
                ['email', '=', $email, PDO::PARAM_STR],
                ['password', '=', $password, PDO::PARAM_STR]
            ]
        );
    }

    public function Register($email, $password, $role = 'new', $state = 'inactive') {
        $user = APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users',
            Array(
                'id'            => 'NULL',
                'email'         => [$email, PDO::PARAM_STR],
                'password'      => [APP::Module('Crypt')->Encode($password), PDO::PARAM_STR],
                'role'          => [$role, PDO::PARAM_STR],
                'reg_date'      => 'NOW()',
                'last_visit'    => 'NOW()',
            )
        );

        APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users_about',
            Array(
                'id' => 'NULL',
                'user' => [$user, PDO::PARAM_INT],
                'item' => ['state', PDO::PARAM_STR],
                'value' => [$state, PDO::PARAM_STR],
                'up_date' => 'NOW()'
            )
        );

        return $user;
    }

    public function Auth($id, $set_cookie = true, $save_password = false) {
        $user = APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['*'], 'users', [['id', '=', $id, PDO::PARAM_INT]]);

        if ($set_cookie) {
            setcookie(
                'modules[users][email]',
                $user['email'],
                strtotime('+' . $this->settings['module_users_timeout_email']),
                APP::$conf['location'][2],
                APP::$conf['location'][1]
            );

            setcookie(
                'modules[users][token]',
                APP::Module('Crypt')->Encode($user['password']),
                $save_password ? strtotime('+' . $this->settings['module_users_timeout_token']) : 0,
                APP::$conf['location'][2],
                APP::$conf['location'][1]
            );
        }

        return $user;
    }

    public function CheckRules() {
        $rules = [];

        foreach (APP::Module('Registry')->Get(['module_users_role'], ['id', 'value'])['module_users_role'] as $role) {
            $rule = (array) APP::Module('Registry')->Get(['module_users_rule'], 'value', $role['id']);
            $rules[$role['value']] = array_key_exists('module_users_rule', $rule) ? (array) $rule['module_users_rule'] : [];
        }

        if (array_key_exists($this->user['role'], $rules)) {
            foreach ($rules[$this->user['role']] as $rule) {
                $rule_data = json_decode($rule, 1);

                if (preg_match('/^' . preg_quote(APP::$conf['location'][2], '/') . $rule_data[0] . '$/', APP::Module('Routing')->RequestURI())) {
                    return $rule_data[1];
                }
            }
        }

        return false;
    }

    public function GeneratePassword($length, $letters = true, $numbers = true, $other = false) {
        $chars = [];

        if ($letters) {
            $chars = array_merge($chars, [
                'a','b','c','d','e','f',
                'g','h','i','j','k','l',
                'm','n','o','p','r','s',
                't','u','v','x','y','z',
                'A','B','C','D','E','F',
                'G','H','I','J','K','L',
                'M','N','O','P','R','S',
                'T','U','V','X','Y','Z'
            ]);
        }

        if ($numbers) {
            $chars = array_merge($chars, [
                '1','2','3','4','5','6',
                '7','8','9','0'
            ]);
        }

        if ($other) {
            $chars = array_merge($chars, [
                '.',',',
                '(',')','[',']','!','?',
                '&','^','%','@','*','$',
                '<','>','/','|','+','-',
                '{','}','`','~'
            ]);
        }

        $pass = '';

        for($i = 0; $i < $length; $i++) {
            $index = rand(0, count($chars) - 1);
            $pass .= $chars[$index];
        }

        return $pass;
    }

    public function Logout() {
        APP::Module('Triggers')->Exec('user_logout', $this->user);

        if (isset(APP::Module('Routing')->get['account']) ? (bool) APP::Module('Routing')->get['account'] : false) {
            setcookie(
                'modules[users][email]', '',
                strtotime('-' . $this->settings['module_users_timeout_email']),
                APP::$conf['location'][2], APP::$conf['location'][1]
            );
        }

        setcookie(
            'modules[users][token]', '',
            strtotime('-' . $this->settings['module_users_timeout_token']),
            APP::$conf['location'][2], APP::$conf['location'][1]
        );

        $this->user = false;

        header('Location: ' . APP::Module('Routing')->root);
        exit;
    }

    public function SaveAbout($user) {
        $data = [
            'remote_addr' => APP::Module('Utils')->ClientIP(),
            'self_url' => APP::Module('Routing')->SelfUrl()
        ];

        if (isset($_SERVER['HTTP_USER_AGENT'])) $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $data['http_accept_language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        if (isset($_SERVER['HTTP_REFERER'])) $data['http_referer'] = $_SERVER['HTTP_REFERER'];

        foreach ($data as $item => $value) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN],
                ['COUNT(id)'], 'users_about',
                [
                    ['user', '=', $user, PDO::PARAM_INT],
                    ['item', '=', $item, PDO::PARAM_STR]
                ]
            )) {
                APP::Module('DB')->Insert(
                    $this->settings['module_users_db_connection'], 'users_about',
                    Array(
                        'id' => 'NULL',
                        'user' => [$user, PDO::PARAM_INT],
                        'item' => [$item, PDO::PARAM_STR],
                        'value' => [$value, PDO::PARAM_STR],
                        'up_date' => 'NOW()'
                    )
                );
            }
        }

        APP::Module('Triggers')->Exec('user_save_about', [
            'user' => $user,
            'data' => $data
        ]);
    }

    public function SaveUTMLabels($user) {
        $num = (int) APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN],
            ['num'], 'users_utm', [['user_id', '=', $user, PDO::PARAM_INT]],
            false, false, false,
            ['num', 'DESC']
        );

        if ($num) {
            $num ++;
        } else {
            $num = 1;
        }

        $labels = array();
        $values = array();

        foreach ($_GET as $par_key => $par_value) {
            if (preg_match('/^utm_(.*)$/', $par_key, $utm_data)) {
                $values[] = $this->AddUTMLabel($user, $utm_data[1], $par_value, $num);
                $labels[] = $utm_data[1];
            }
        }

        foreach ($_POST as $par_key => $par_value) {
            if (preg_match('/^utm_(.*)$/', $par_key, $utm_data))  {
                $values[] = $this->AddUTMLabel($user, $utm_data[1], $par_value, $num);
                $labels[] = $utm_data[1];
            }
        }

        if ($num == 1) {
            foreach (['content', 'term', 'campaign', 'medium', 'source'] as $label) {
                if (!in_array($label, $labels)){
                    $values[] = $this->AddUTMLabel($user, $label, '', $num);
                }
            }
        }

        APP::Module('Triggers')->Exec('user_save_utm', [
            'user' => $user,
            'data' => $values
        ]);
    }

    public function AddUTMLabel($user, $item, $value, $num = 1) {
        if (!empty($value) || $num == 1) {
            return APP::Module('DB')->Insert(
                $this->settings['module_users_db_connection'], 'users_about',
                [
                    'id' => 'NULL',
                    'user' => [$user, PDO::PARAM_INT],
                    'num' => [$num, PDO::PARAM_INT],
                    'item' => [$item, PDO::PARAM_STR],
                    'value' => [$value, PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                ]
            );
        }

        return false;
    }


    public function NewUsersGC() {
        $lock = fopen($this->settings['module_users_tmp_dir'] . '/module_users_new_users_gc.lock', 'w');

        if (flock($lock, LOCK_EX|LOCK_NB)) {
            APP::Module('DB')->Delete(
                $this->settings['module_users_db_connection'], 'users',
                [
                    ['role', '=', 'new', PDO::PARAM_STR],
                    ['UNIX_TIMESTAMP(reg_date)', '<=', strtotime('-' . $this->settings['module_users_timeout_activation']), PDO::PARAM_INT]
                ]
            );
        } else {
            exit;
        }

        fclose($lock);
    }


    public function ManageUsers() {
        APP::Render('users/admin/index');
    }

    public function AddUser() {
        APP::Render('users/admin/add', 'include', ['roles' => APP::Module('Registry')->Get(['module_users_role'])['module_users_role']]);
    }

    public function EditUser() {
        $user_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_id_hash']);

        APP::Render(
            'users/admin/edit', 'include',
            [
                'user' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
                    [['id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'roles' => APP::Module('Registry')->Get(['module_users_role'])['module_users_role']
            ]
        );
    }

    public function Actions() {
        $return = isset(APP::Module('Routing')->get['return']) ? APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['return']) : false;
        APP::Render('users/actions', 'include', ['return' => $return ? filter_var($return, FILTER_VALIDATE_URL) ? $return : false : false]);
    }

    public function DoubleLoginForm() {
        $return = isset(APP::Module('Routing')->get['return_hash']) ? APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['return_hash']) : false;

        APP::Render(
            'users/double_login', 'include',
            [
                'return' => $return ? filter_var($return, FILTER_VALIDATE_URL) ? $return : false : false,
                'email' => $this->user['email']
            ]
        );
    }

    public function Activate() {
        $result = 'success';

        $user_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_id_hash']);
        $params = isset(APP::Module('Routing')->get['params']) ? json_decode(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['params']), 1) : [];

        if (APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users', [['id', '=', $user_id, PDO::PARAM_INT]])) {
            APP::Module('DB')->Update(
                $this->settings['module_users_db_connection'], 'users',
                ['role' => 'user'],
                [
                    ['id', '=', $user_id, PDO::PARAM_INT],
                    ['role', '!=', 'user', PDO::PARAM_STR]
                ]
            );

            APP::Module('DB')->Update(
                $this->settings['module_users_db_connection'], 'users_about',
                ['value' => 'active'],
                [
                    ['user', '=', $user_id, PDO::PARAM_INT],
                    ['item', '=', 'state', PDO::PARAM_STR],
                    ['value', '!=', 'active', PDO::PARAM_STR]
                ]
            );

            APP::Module('DB')->Delete(
                APP::Module('TaskManager')->settings['module_taskmanager_db_connection'], 'task_manager',
                [
                    ['token', '=', $user_id . '_tunnel_activation', PDO::PARAM_STR],
                    ['state', '=', 'wait', PDO::PARAM_STR]
                ]
            );

            APP::Module('Triggers')->Exec('user_activate', [
                'user_id' => $user_id,
                'params' => $params
            ]);
        } else {
            $result = 'error';
        }

        if (isset($params['return'])) {
            if ($params['return']) {
                header('Location: ' . $params['return']);
                exit;
            }
        }

        APP::Render('users/activate', 'include', $result);
    }

    public function ManageRoles() {
        APP::Render('users/admin/roles/index');
    }

    public function AddRole() {
        APP::Render('users/admin/roles/add');
    }

    public function ManageRules() {
        APP::Render('users/admin/roles/rules/index', 'include', [
            'role' => APP::Module('DB')->Select(
                APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
                ['value'], 'registry',
                [['id', '=', APP::Module('Crypt')->Decode(APP::Module('Routing')->get['role_id_hash']), PDO::PARAM_INT]]
            )
        ]);
    }

    public function AddRule() {
        $role_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['role_id_hash']);

        APP::Render('users/admin/roles/rules/add', 'include', APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
            ['value'], 'registry',
            [['id', '=', $role_id, PDO::PARAM_INT]]
        ));
    }

    public function EditRule() {
        $role_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['role_id_hash']);
        $rule_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['rule_id_hash']);

        APP::Render('users/admin/roles/rules/edit', 'include', [
            'role' => APP::Module('DB')->Select(
                APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
                ['value'], 'registry',
                [['id', '=', $role_id, PDO::PARAM_INT]]
            ),
            'rule' => json_decode(APP::Module('DB')->Select(
                APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
                ['value'], 'registry',
                [['id', '=', $rule_id, PDO::PARAM_INT]]
            ), 1)
        ]);
    }

    public function SetupOAuthClients() {
        APP::Render('users/admin/oauth_clients');
    }

    public function SetupNotifications() {
        APP::Render('users/admin/notifications');
    }

    public function SetupServices() {
        APP::Render('users/admin/services');
    }

    public function SetupAuth() {
        APP::Render('users/admin/auth');
    }

    public function SetupPasswords() {
        APP::Render('users/admin/passwords');
    }

    public function SetupTimeouts() {
        APP::Render('users/admin/timeouts');
    }

    public function SetupOther() {
        APP::Render('users/admin/settings');
    }


    public function PublicProfile() {
        if (!isset(APP::Module('Routing')->get['user_id_hash'])) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $user_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_id_hash']);
        $about = [];
        $comments = false;

        if ((!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'users',
            [['id', '=', $user_id, PDO::PARAM_INT]]
        )) || (!$user_id)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['item', 'value'], 'users_about',
            [['user', '=', $user_id, PDO::PARAM_INT]]
        ) as $value) {
            $about[$value['item']] = $value['value'];
        }

        APP::Render(
            'users/profiles/public', 'include',
            [
                'user' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
                    [['id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'social-profiles' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                    ['service', 'extra'], 'users_accounts',
                    [['user_id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'about' => $about,
            ]
        );
    }

    public function PrivateProfile() {
        $about = [];
        $comments = false;
        $likes = false;
        $premium = false;

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['item', 'value'], 'users_about',
            [['user', '=', $this->user['id'], PDO::PARAM_INT]]
        ) as $value) {
            $about[$value['item']] = $value['value'];
        }

        if (isset(APP::$modules['Comments'])) {
            $comments = APP::Module('DB')->Select(
                APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['id', 'message', 'url', 'UNIX_TIMESTAMP(up_date) as up_date'], 'comments_messages',
                [['user', '=', $this->user['id'], PDO::PARAM_INT]],
                false, false, false,
                ['id', 'desc']
            );
        }

        if (isset(APP::$modules['Likes'])) {
            $likes = APP::Module('DB')->Select(
                APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['id', 'url', 'UNIX_TIMESTAMP(up_date) as up_date'], 'likes_list',
                [['user', '=', $this->user['id'], PDO::PARAM_INT]],
                false, false, false,
                ['id', 'desc']
            );
        }
        
        if (isset(APP::$modules['Members'])) {
            foreach (APP::Module('DB')->Select(
                APP::Module('Members')->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['item', 'item_id'], 'members_access',
                [['user_id', '=', $this->user['id'], PDO::PARAM_INT]]
            ) as $value) {
                $table = false;
                $title = false;
                
                switch ($value['item']) {
                    case 'g': 
                        $table = 'members_pages_groups';
                        $title = 'name'; 
                        break;
                    case 'p': 
                        $table = 'members_pages'; 
                        $title = 'title'; 
                        break;
                }
                
                $premium[] = [
                    'type' => $value['item'],
                    'id' => $value['item_id'],
                    'title' => APP::Module('DB')->Select(
                        APP::Module('Members')->settings['module_members_db_connection'], ['fetch', PDO::FETCH_COLUMN],
                        [$title . ' AS name'], $table,
                        [['id', '=', $value['item_id'], PDO::PARAM_INT]]
                    )
                ];
            }
        }

        APP::Render(
            'users/profiles/private', 'include',
            [
                'user' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
                    [['id', '=', $this->user['id'], PDO::PARAM_INT]]
                ),
                'social-profiles' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                    ['service', 'extra'], 'users_accounts',
                    [['user_id', '=', $this->user['id'], PDO::PARAM_INT]]
                ),
                'about' => $about,
                'comments' => $comments,
                'likes' => $likes,
                'premium' => $premium
            ]
        );
    }

    public function AdminProfile() {
        $user_id = APP::Module('Routing')->get['user_id'];
        
        $about = [];
        $mail = false;
        $tunnels = false;
        $tags = false;
        $comments = false;
        $likes = false;
        $premium = false;

        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'users',
            [['id', '=', $user_id, PDO::PARAM_INT]]
        )) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['item', 'value'], 'users_about',
            [['user', '=', $user_id, PDO::PARAM_INT]]
        ) as $value) {
            $about[$value['item']] = $value['value'];
        }
        
        $mail_id = [];
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            [
                'mail_log.id', 
                'mail_log.letter', 
                'mail_log.sender', 
                'mail_log.transport', 
                'mail_log.state', 
                'mail_log.result', 
                'mail_log.retries', 
                'mail_log.ping', 
                'mail_log.cr_date',
                
                'mail_letters.subject AS letter_subject',
                'mail_letters.priority AS letter_priority',
                
                'mail_senders.name AS sender_name',
                'mail_senders.email AS sender_email',
                
                'mail_transport.module AS transport_module',
                'mail_transport.method AS transport_method'
            ], 
            'mail_log',
            [['user', '=', $user_id, PDO::PARAM_INT]],
            [
                'join/mail_letters' => [['mail_letters.id', '=', 'mail_log.letter']],
                'join/mail_senders' => [['mail_senders.id', '=', 'mail_log.sender']],
                'join/mail_transport' => [['mail_transport.id', '=', 'mail_log.transport']]
            ],
            ['mail_log.id'],
            false,
            ['mail_log.id', 'DESC']
        ) as $value) {
            $mail_id[] = $value['id'];
            $mail[$value['id']] = [
                'log' => $value,
                'events' => [],
                'tags' => []
            ];
        }
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'log', 'event', 'details', 'cr_date'], 'mail_events',
            [['log', 'IN', $mail_id]],
            false, false, false,
            ['id', 'DESC']
        ) as $value) {
            $mail[$value['log']]['events'][] = $value;
            $mail[$value['log']]['tags'][] = $value['event'];
        }

        $tunnels_id = [];
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            [
                'tunnels_users.id', 
                'tunnels_users.tunnel_id', 
                'tunnels_users.state', 
                
                'tunnels.type AS tunnel_type',
                'tunnels.name AS tunnel_name'
            ], 
            'tunnels_users',
            [['user_id', '=', $user_id, PDO::PARAM_INT]],
            [
                'join/tunnels' => [['tunnels.id', '=', 'tunnels_users.tunnel_id']]
            ],
            ['tunnels_users.id'],
            false,
            ['tunnels_users.id', 'DESC']
        ) as $value) {
            $tunnels_id[] = $value['id'];
            $tunnels[$value['id']] = [
                'info' => $value,
                'tags' => []
            ];
        }
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'user_tunnel_id', 'label_id', 'info', 'cr_date'], 'tunnels_tags',
            [['user_tunnel_id', 'IN', $tunnels_id]],
            false, false, false,
            ['id', 'DESC']
        ) as $value) {
            $tunnels[$value['user_tunnel_id']]['tags'][] = $value;
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            [
                'users_tags.id', 
                'users_tags.item', 
                'users_tags.value', 
                'users_tags.cr_date'
            ], 
            'users_tags',
            [['user', '=', $user_id, PDO::PARAM_INT]],
            false, false, false,
            ['users_tags.id', 'DESC']
        ) as $value) {
            $tags[$value['id']] = $value;
        }

        if (isset(APP::$modules['Comments'])) {
            $comments = APP::Module('DB')->Select(
                APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['id', 'message', 'url', 'UNIX_TIMESTAMP(up_date) as up_date'], 'comments_messages',
                [['user', '=', $user_id, PDO::PARAM_INT]],
                false, false, false,
                ['id', 'desc']
            );
        }

        if (isset(APP::$modules['Likes'])) {
            $likes = APP::Module('DB')->Select(
                APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['id', 'url', 'UNIX_TIMESTAMP(up_date) as up_date'], 'likes_list',
                [['user', '=', $user_id, PDO::PARAM_INT]],
                false, false, false,
                ['id', 'desc']
            );
        }
        
        if (isset(APP::$modules['Members'])) {
            foreach (APP::Module('DB')->Select(
                APP::Module('Members')->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['item', 'item_id'], 'members_access',
                [['user_id', '=', $user_id, PDO::PARAM_INT]]
            ) as $value) {
                $table = false;
                $title = false;
                
                switch ($value['item']) {
                    case 'g': 
                        $table = 'members_pages_groups';
                        $title = 'name'; 
                        break;
                    case 'p': 
                        $table = 'members_pages'; 
                        $title = 'title'; 
                        break;
                }
                
                $premium[] = [
                    'type' => $value['item'],
                    'id' => $value['item_id'],
                    'title' => APP::Module('DB')->Select(
                        APP::Module('Members')->settings['module_members_db_connection'], ['fetch', PDO::FETCH_COLUMN],
                        [$title . ' AS name'], $table,
                        [['id', '=', $value['item_id'], PDO::PARAM_INT]]
                    )
                ];
            }
        }

        APP::Render(
            'users/admin/profile', 'include',
            [
                'user' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
                    [['id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'social-profiles' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                    ['service', 'extra'], 'users_accounts',
                    [['user_id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'about' => $about,
                'mail' => $mail,
                'tunnels' => $tunnels,
                'tags' => $tags,
                'comments' => $comments,
                'likes' => $likes,
                'premium' => $premium
            ]
        );
    }

    public function APISearchUsers() {
        $request = json_decode(file_get_contents('php://input'), true);
        $out = $this->UsersSearch(json_decode($request['search'], 1));
        $rows = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
            [['id', 'IN', $out, PDO::PARAM_INT]], 
            false, false, false,
            [$request['sort_by'], $request['sort_direction']],
            $request['rows'] === -1 ? false : [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['auth_token'] = APP::Module('Crypt')->Encode(json_encode([$row['email'], $row['password']]));
            $row['user_id_token'] = APP::Module('Crypt')->Encode($row['id']);
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
    
    public function APISearchUsersAction() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($this->users_actions->{$_POST['action']}($this->UsersSearch(json_decode($_POST['rules'], 1)), isset($_POST['settings']) ? $_POST['settings'] : false));
        exit;
    }

    public function APIListUsers() {
        $rows = [];
        $where = [['id', '!=', 0, PDO::PARAM_INT]];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
            $_POST['searchPhrase'] ? array_merge([['email', 'LIKE', $_POST['searchPhrase'] . '%' ]], $where) : $where,
            false, false, false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['auth_token'] = APP::Module('Crypt')->Encode(json_encode([$row['email'], $row['password']]));
            $row['user_id_token'] = APP::Module('Crypt')->Encode($row['id']);

            array_push($rows, $row);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', $_POST['searchPhrase'] ? array_merge([['email', 'LIKE', $_POST['searchPhrase'] . '%' ]], $where) : $where)
        ]);
        exit;
    }

    public function APIRemoveUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'users',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_users_db_connection'], 'users',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('remove_user', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIAddUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (empty($_POST['password'])) {
            $_POST['password'] = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
        } else if (strlen($_POST['password']) < (int) $this->settings['module_users_min_pass_length']) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if ($out['status'] == 'success') {
            $user_id = $this->Register($_POST['email'], $_POST['password'], $_POST['role']);

            if ((int) $_POST['notification']) {
                APP::Module('Mail')->Send($_POST['email'], $_POST['notification'], [
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'expire' => strtotime('+' . $this->settings['module_users_timeout_activation']),
                    'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user_id) . '/'
                ]);
            }

            $out['user_id'] = $user_id;

            APP::Module('Triggers')->Exec('add_user', [
                'id' => $out['user_id'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => $_POST['role'],
                'notification' => $_POST['notification']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APILogin() {
        $status = 'error';

        if (($user = $this->Login($_POST['email'], APP::Module('Crypt')->Encode($_POST['password']))) && ((int) $this->settings['module_users_login_service'])) {
            $this->user = $this->Auth($user, true, isset($_POST['remember-me']));

            $status = 'success';

            APP::Module('Triggers')->Exec('user_login', [
                'id' => $user,
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'remember-me' => isset($_POST['remember-me'])
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        echo json_encode(['status' => $status]);
        exit;
    }

    public function APIDoubleLogin() {
        $status = 'error';

        if ($this->user['password'] === APP::Module('Crypt')->Encode($_POST['password'])) {
            APP::Module('Triggers')->Exec('user_double_login', $this->user);
            APP::Module('Sessions')->session['modules']['users']['double_auth'] = true;
            $status = 'success';
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        echo json_encode(['status' => $status]);
        exit;
    }

    public function APIRegister() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if (strlen($_POST['password']) < (int) $this->settings['module_users_min_pass_length']) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }

        if (!(int) $this->settings['module_users_register_service']) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }

        if ($out['status'] == 'success') {
            $user_id = $this->Register($_POST['email'], $_POST['password']);
            $this->user = $this->Auth($user_id, true, false);

            APP::Module('Mail')->Send($_POST['email'], $this->settings['module_users_register_activation_letter'], [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'expire' => strtotime('+' . $this->settings['module_users_timeout_activation']),
                'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user_id) . '/'
            ]);

            $out['user_id'] = $user_id;

            APP::Module('Triggers')->Exec('register_user', [
                'id' => $out['user_id'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APISubscribe() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (!(int) $this->settings['module_users_register_service']) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }

        if ($out['status'] == 'success') {
            $password = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
            $user_id = $this->Register($_POST['email'], $password);
            $this->user = $this->Auth($user_id, true, false);

            APP::Module('Mail')->Send($_POST['email'], $this->settings['module_users_subscribe_activation_letter'], [
                'email' => $_POST['email'],
                'password' => $password,
                'expire' => strtotime('+' . $this->settings['module_users_timeout_activation']),
                'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user_id) . '/'
            ]);

            $out['user_id'] = $user_id;

            APP::Module('Triggers')->Exec('subscribe_user', [
                'id' => $out['user_id'],
                'email' => $_POST['email'],
                'password' => $password
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIResetPassword() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (!APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (!(int) $this->settings['module_users_reset_password_service']) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }

        if ($out['status'] == 'success') {
            $out = [
                'status' => 'success',
                'info' => APP::Module('Mail')->Send($_POST['email'], $this->settings['module_users_reset_password_letter'], [
                    'link' => APP::Module('Routing')->root . 'users/actions/change-password?user_token=' . APP::Module('Crypt')->Encode(json_encode([
                        $_POST['email'],
                        APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['password'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])
                    ]))
                ])
            ];

            APP::Module('Triggers')->Exec('reset_user_password', ['email' => $_POST['email']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIChangePassword() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!isset($this->user['id'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        } else if (strlen($_POST['password']) < (int) $this->settings['module_users_min_pass_length']) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if (!(int) $this->settings['module_users_change_password_service']) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }

        if ($out['status'] == 'success') {
            APP::Module('Triggers')->Exec('change_user_password', [
                'user' => $this->user,
                'password' => $_POST['password']
            ]);

            APP::Module('DB')->Update(
                $this->settings['module_users_db_connection'], 'users',
                ['password' => APP::Module('Crypt')->Encode($_POST['password'])],
                [['id', '=', $this->user['id'], PDO::PARAM_INT]]
            );

            $this->user = $this->Auth($this->user['id'], true, true);

            $out = [
                'status' => 'success',
                'info' => APP::Module('Mail')->Send($this->user['email'], $this->settings['module_users_change_password_letter'], [
                    'email' => $this->user['email'],
                    'password' => $_POST['password']
                ])
            ];
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APILogout() {
        APP::Module('Triggers')->Exec('user_logout', $this->user);

        if (isset(APP::Module('Routing')->get['account']) ? (bool) APP::Module('Routing')->get['account'] : false) {
            setcookie(
                'modules[users][email]', '',
                strtotime('-' . $this->settings['module_users_timeout_email']),
                APP::$conf['location'][2], APP::$conf['location'][1]
            );
        }

        setcookie(
            'modules[users][token]', '',
            strtotime('-' . $this->settings['module_users_timeout_token']),
            APP::$conf['location'][2], APP::$conf['location'][1]
        );

        $this->user = false;

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode(['result' => true]);
        exit;
    }

    public function APIUpdateUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $user_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', $user_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
            if (empty($_POST['password'])) {
                $out['status'] = 'error';
                $out['errors'][] = 2;
            } else if (strlen($_POST['password']) < (int) $this->settings['module_users_min_pass_length']) {
                $out['status'] = 'error';
                $out['errors'][] = 3;
            } else if ($_POST['password'] != $_POST['re-password']) {
                $out['status'] = 'error';
                $out['errors'][] = 4;
            }
        }

        if (array_search($_POST['role'], APP::Module('Registry')->Get(['module_users_role'])['module_users_role']) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_users_db_connection'], 'users', ['role' => $_POST['role']], [['id', '=', $user_id, PDO::PARAM_INT]]);

            if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
                APP::Module('DB')->Update($this->settings['module_users_db_connection'], 'users', ['password' => APP::Module('Crypt')->Encode($_POST['password'])], [['id', '=', $user_id, PDO::PARAM_INT]]);
            }

            APP::Module('Triggers')->Exec('update_user', [
                'id' => $user_id,
                'password' => APP::Module('Sessions')->session['modules']['users']['double_auth'] ? $_POST['password'] : false,
                'role' => $_POST['role']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIListRoles() {
        $roles = [];
        $rows = [];

        foreach (APP::Module('Registry')->Get(['module_users_role'], ['id', 'value'])['module_users_role'] as $role) {
            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', $role['value']) === 0)) continue;
            array_push($roles, $role);
        }

        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($roles[$x])) continue;
            $roles[$x]['token'] = APP::Module('Crypt')->Encode($roles[$x]['id']);
            array_push($rows, $roles[$x]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($roles)
        ]);
        exit;
    }

    public function APIAddRole() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['role'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['role_id'] = APP::Module('Registry')->Add('module_users_role', $_POST['role']);
            APP::Module('Triggers')->Exec('add_user_role', ['id' => $out['role_id'], 'role' => $_POST['role']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemoveRole() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'registry',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('Registry')->Delete([['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            $out['count'] = APP::Module('Registry')->Delete([['sub_id', '=', $_POST['id'], PDO::PARAM_INT]]);

            APP::Module('Triggers')->Exec('remove_user_role', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIListRules() {
        $tmp_rules = APP::Module('Registry')->Get(['module_users_rule'], ['id', 'value'], isset($_POST['role']) ? $_POST['role'] : 0);

        $rules = [];
        $rows = [];

        foreach (array_key_exists('module_users_rule', $tmp_rules) ? (array) $tmp_rules['module_users_rule'] : [] as $rule) {
            $rule_value = json_decode($rule['value'], 1);
            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', $rule_value[0]) === 0)) continue;

            array_push($rules, [
                'id' => $rule['id'],
                'pattern' => $rule_value[0],
                'target' => $rule_value[1],
                'token' => APP::Module('Crypt')->Encode($rule['id'])
            ]);
        }

        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($rules[$x])) continue;
            array_push($rows, $rules[$x]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($rules)
        ]);
        exit;
    }

    public function APIAddRule() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $role_id = APP::Module('Crypt')->Decode($_POST['role']);

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'registry',
            [['id', '=', $role_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['uri_pattern'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (empty($_POST['target'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }

        if ($out['status'] == 'success') {
            $out['rule_id'] = APP::Module('Registry')->Add('module_users_rule', json_encode([$_POST['uri_pattern'], $_POST['target']]), $role_id);

            APP::Module('Triggers')->Exec('add_user_rule', [
                'id' => $out['rule_id'],
                'role_id' => $role_id,
                'uri_pattern' => $_POST['uri_pattern'],
                'target' => $_POST['target']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemoveRule() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'registry',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('Registry')->Delete([['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('remove_user_rule', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateRule() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $rule_id = APP::Module('Crypt')->Decode($_POST['rule']);

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'registry',
            [['id', '=', $rule_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['uri_pattern'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if (empty($_POST['target'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }

        if ($out['status'] == 'success') {
            APP::Module('Registry')->Update(['value' => json_encode([$_POST['uri_pattern'], $_POST['target']])], [['id', '=', $rule_id, PDO::PARAM_INT]]);

            APP::Module('Triggers')->Exec('update_user_rule', [
                'id' => $rule_id,
                'rule' => $_POST['rule'],
                'uri_pattern' => $_POST['uri_pattern'],
                'target' => $_POST['target']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateOAuthClientSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_fb_id']], [['item', '=', 'module_users_oauth_client_fb_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_fb_key']], [['item', '=', 'module_users_oauth_client_fb_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_vk_id']], [['item', '=', 'module_users_oauth_client_vk_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_vk_key']], [['item', '=', 'module_users_oauth_client_vk_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_google_id']], [['item', '=', 'module_users_oauth_client_google_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_google_key']], [['item', '=', 'module_users_oauth_client_google_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_ya_id']], [['item', '=', 'module_users_oauth_client_ya_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_oauth_client_ya_key']], [['item', '=', 'module_users_oauth_client_ya_key', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_oauth_settings', [
            'oauth_client_fb_id' => $_POST['module_users_oauth_client_fb_id'],
            'oauth_client_fb_key' => $_POST['module_users_oauth_client_fb_key'],
            'oauth_client_vk_id' => $_POST['module_users_oauth_client_vk_id'],
            'oauth_client_vk_key' => $_POST['module_users_oauth_client_vk_key'],
            'oauth_client_google_id' => $_POST['module_users_oauth_client_google_id'],
            'oauth_client_google_key' => $_POST['module_users_oauth_client_google_key'],
            'oauth_client_ya_id' => $_POST['module_users_oauth_client_ya_id'],
            'oauth_client_ya_key' => $_POST['module_users_oauth_client_ya_key']
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

    public function APIUpdateNotificationsSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_users_register_activation_letter']], [['item', '=', 'module_users_register_activation_letter', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_reset_password_letter']], [['item', '=', 'module_users_reset_password_letter', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_register_letter']], [['item', '=', 'module_users_register_letter', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_change_password_letter']], [['item', '=', 'module_users_change_password_letter', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_subscription_restore_letter']], [['item', '=', 'module_users_subscription_restore_letter', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('update_users_notifications_settings', [
            'register_activation_letter' => $_POST['module_users_register_activation_letter'],
            'reset_password_letter' => $_POST['module_users_reset_password_letter'],
            'register_letter' => $_POST['module_users_register_letter'],
            'change_password_letter' => $_POST['module_users_change_password_letter'],
            'subscription_restore_letter' => $_POST['module_users_subscription_restore_letter']
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

    public function APIUpdateServicesSettings() {
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_login_service'])], [['item', '=', 'module_users_login_service', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_register_service'])], [['item', '=', 'module_users_register_service', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_reset_password_service'])], [['item', '=', 'module_users_reset_password_service', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_change_password_service'])], [['item', '=', 'module_users_change_password_service', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_services_settings', [
            'login_service' => isset($_POST['module_users_login_service']),
            'register_service' => isset($_POST['module_users_register_service']),
            'reset_password_service' => isset($_POST['module_users_reset_password_service']),
            'change_password_service' => isset($_POST['module_users_change_password_service'])
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

    public function APIUpdateAuthSettings() {
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_check_rules'])], [['item', '=', 'module_users_check_rules', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_users_auth_token'])], [['item', '=', 'module_users_auth_token', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_auth_settings', [
            'check_rules' => isset($_POST['module_users_check_rules']),
            'auth_token' => isset($_POST['module_users_auth_token'])
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

    public function APIUpdatePasswordsSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_users_min_pass_length']], [['item', '=', 'module_users_min_pass_length', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_gen_pass_length']], [['item', '=', 'module_users_gen_pass_length', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_passwords_settings', [
            'min_pass_length' => $_POST['module_users_min_pass_length'],
            'gen_pass_length' => $_POST['module_users_gen_pass_length']
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

    public function APIUpdateTimeoutsSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_users_timeout_token']], [['item', '=', 'module_users_timeout_token', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_timeout_email']], [['item', '=', 'module_users_timeout_email', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_timeout_activation']], [['item', '=', 'module_users_timeout_activation', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_timeouts_settings', [
            'timeout_token' => $_POST['module_users_timeout_token'],
            'timeout_email' => $_POST['module_users_timeout_email'],
            'timeout_activation' => $_POST['module_users_timeout_activation']
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

    public function APIUpdateOtherSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_users_db_connection']], [['item', '=', 'module_users_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_tmp_dir']], [['item', '=', 'module_users_tmp_dir', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_profile_picture']], [['item', '=', 'module_users_profile_picture', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_other_settings', [
            'db_connection' => $_POST['module_users_db_connection'],
            'tmp_dir' => $_POST['module_users_tmp_dir'],
            'profile_picture' => $_POST['module_users_profile_picture']
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

    public function APIAddAbout() {
        $id = APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users_about',
            [
                'id' => 'NULL',
                'user' => [$_POST['user'], PDO::PARAM_INT],
                'item' => [$_POST['item'], PDO::PARAM_STR],
                'value' => [$_POST['value'], PDO::PARAM_STR],
                'up_date' => 'NOW()'
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
    
    public function APIUpdateAbout() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!isset($_POST['about'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Delete(
                $this->settings['module_users_db_connection'], 'users_about',
                [
                    ['user', '=', $this->user['id'], PDO::PARAM_INT],
                    ['item', 'IN', array_keys((array) $_POST['about'])]
                ]
            );

            foreach ((array) $_POST['about'] as $item => $value) {
                if ((!empty($value)) && (array_search($item, $this->about) !== false)) {
                    APP::Module('DB')->Insert(
                        $this->settings['module_users_db_connection'], ' users_about',
                        [
                            'id' => 'NULL',
                            'user' => [$this->user['id'], PDO::PARAM_INT],
                            'item' => [$item, PDO::PARAM_STR],
                            'value' => [$value, PDO::PARAM_STR],
                            'up_date' => 'CURRENT_TIMESTAMP'
                        ]
                    );
                }
            }

            APP::Module('Triggers')->Exec('update_about_user', [
                'user' => $this->user['id'],
                'about' => (array) $_POST['about']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIAdminUpdateAbout() {
        $out = [
            'status' => 'success',
            'errors' => [],
            'items' => []
        ];

        $user_id = APP::Module('Crypt')->Decode($_POST['user']);

        if (!APP::Module('DB')->Select($this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', $user_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Delete(
                $this->settings['module_users_db_connection'], 'users_about',
                [
                    ['user', '=', $user_id, PDO::PARAM_INT],
                    ['item', 'IN', array_keys($_POST['about'])]
                ]
            );

            foreach ($_POST['about'] as $item => $value) {
                if ((!empty($value)) && (array_search($item, $this->about) !== false)) {
                    $out['items'][$item] = APP::Module('DB')->Insert(
                        $this->settings['module_users_db_connection'], ' users_about',
                        [
                            'id' => 'NULL',
                            'user' => [$user_id, PDO::PARAM_INT],
                            'item' => [$item, PDO::PARAM_STR],
                            'value' => [$value, PDO::PARAM_STR],
                            'up_date' => 'CURRENT_TIMESTAMP'
                        ]
                    );
                }
            }

            APP::Module('Triggers')->Exec('update_about_user', [
                'user' => $user_id,
                'about' => $_POST['about']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }


    public function LoginVK() {
        if (!(int) $this->settings['module_users_login_service']) APP::Render('users/errors', 'include', 'auth_service');

        if (isset(APP::Module('Routing')->get['code'])) {
            $vk_result = json_decode(file_get_contents('https://oauth.vk.com/access_token?' . urldecode(http_build_query(['client_id' => $this->settings['module_users_oauth_client_vk_id'], 'client_secret' => $this->settings['module_users_oauth_client_vk_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/vk']))), true);

            if (isset($vk_result['user_id'])) {
                if ($user_id = APP::Module('DB')->Select(
                        $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                        ['user_id'], 'users_accounts',
                        [
                            ['service', '=', 'vk', PDO::PARAM_STR],
                            ['extra', '=', $vk_result['user_id'], PDO::PARAM_STR]
                        ]
                )) {
                    $this->user = $this->Auth($user_id, true, true);
                } else {
                    if (isset($vk_result['email'])) {
                        if ($user_id = APP::Module('DB')->Select(
                                $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                                ['id'], 'users', [['email', '=', $vk_result['email'], PDO::PARAM_STR]]
                        )) {
                            APP::Module('DB')->Insert(
                                $this->settings['module_users_db_connection'], ' users_accounts',
                                [
                                    'id' => 'NULL',
                                    'user_id' => [$user_id, PDO::PARAM_INT],
                                    'service' => '"vk"',
                                    'extra' => [$vk_result['user_id'], PDO::PARAM_STR],
                                    'up_date' => 'NOW()',
                                ]
                            );

                            $this->user = $this->Auth($user_id, true, true);
                        } else {
                            $password = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
                            $user_id = $this->Register($vk_result['email'], $password, 'user');
                            $this->user = $this->Auth($user_id, true, true);

                            APP::Module('DB')->Insert(
                                $this->settings['module_users_db_connection'], ' users_accounts',
                                [
                                    'id' => 'NULL',
                                    'user_id' => [$user_id, PDO::PARAM_INT],
                                    'service' => '"vk"',
                                    'extra' => [$vk_result['user_id'], PDO::PARAM_STR],
                                    'up_date' => 'NOW()',
                                ]
                            );

                            APP::Module('Mail')->Send($vk_result['email'], $this->settings['module_users_register_letter'], [
                                'email' => $vk_result['email'],
                                'password' => $password
                            ]);
                        }
                    } else {
                        APP::Render('users/errors', 'include', 'auth_vk_email');
                    }
                }
            } else {
                APP::Render('users/errors', 'include', 'auth_vk_user_id');
            }

            header('Location: ' . APP::Module('Crypt')->Decode(json_decode(APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['state']), 1)['return']));
            exit;

            /*
            if (isset($token['access_token'])) {
                $params = array(
                    'uids'         => $token['user_id'],
                    'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                    'access_token' => $token['access_token']
                );

                $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get?' . urldecode(http_build_query($params))), true);

                ?><pre><? print_r($userInfo); ?></pre><?
            } else {
                // ошибка
            }
             *
             */
        } else {
            APP::Render('users/errors', 'include', 'auth_vk_code');
        }
    }

    public function LoginFB() {
        if (!(int) $this->settings['module_users_login_service']) APP::Render('users/errors', 'include', 'auth_service');

        if (isset(APP::Module('Routing')->get['code'])) {
            $fb_result = null;

            parse_str(file_get_contents('https://graph.facebook.com/oauth/access_token?' . urldecode(http_build_query(['client_id' => $this->settings['module_users_oauth_client_fb_id'], 'client_secret' => $this->settings['module_users_oauth_client_fb_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/fb']))), $fb_result);

            if (count($fb_result) > 0 && isset($fb_result['access_token'])) {
                $fb_user = json_decode(file_get_contents('https://graph.facebook.com/me?fields=email&' . urldecode(http_build_query(array('access_token' => $fb_result['access_token'])))), true);

                if (isset($fb_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                        ['user_id'], 'users_accounts',
                        [
                            ['service', '=', 'fb', PDO::PARAM_STR],
                            ['extra', '=', $fb_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($fb_user['email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                                ['id'], 'users', [['email', '=', $fb_user['email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"fb"',
                                        'extra' => [$fb_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
                                $user_id = $this->Register($fb_user['email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"fb"',
                                        'extra' => [$fb_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                APP::Module('Mail')->Send($fb_user['email'], $this->settings['module_users_register_letter'], [
                                    'email' => $fb_user['email'],
                                    'password' => $password
                                ]);
                            }
                        } else {
                            APP::Render('users/errors', 'include', 'auth_fb_email');
                        }
                    }
                } else {
                    APP::Render('users/errors', 'include', 'auth_fb_id');
                }

                header('Location: ' . APP::Module('Crypt')->Decode(json_decode(APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['state']), 1)['return']));
                exit;
            } else {
                APP::Render('users/errors', 'include', 'auth_fb_access_token');
            }
        } else {
            APP::Render('users/errors', 'include', 'auth_fb_code');
        }
    }

    public function LoginGoogle() {
        if (!(int) $this->settings['module_users_login_service']) APP::Render('users/errors', 'include', 'auth_service');

        if (isset(APP::Module('Routing')->get['code'])) {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query(['client_id' => $this->settings['module_users_oauth_client_google_id'], 'client_secret' => $this->settings['module_users_oauth_client_google_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/google', 'grant_type' => 'authorization_code'])));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $google_result = json_decode(curl_exec($curl), true);

            curl_close($curl);

            if (isset($google_result['access_token'])) {
                $google_user = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query(['access_token' => $google_result['access_token']]))), true);

                if (isset($google_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                        ['user_id'], 'users_accounts',
                        [
                            ['service', '=', 'google', PDO::PARAM_STR],
                            ['extra', '=', $google_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($google_user['email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                                ['id'], 'users', [['email', '=', $google_user['email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"google"',
                                        'extra' => [$google_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
                                $user_id = $this->Register($google_user['email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"google"',
                                        'extra' => [$google_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                APP::Module('Mail')->Send($google_user['email'], $this->settings['module_users_register_letter'], [
                                    'email' => $google_user['email'],
                                    'password' => $password
                                ]);
                            }
                        } else {
                            APP::Render('users/errors', 'include', 'auth_google_email');
                        }
                    }
                } else {
                    APP::Render('users/errors', 'include', 'auth_google_id');
                }

                header('Location: ' . APP::Module('Crypt')->Decode(json_decode(APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['state']), 1)['return']));
                exit;
            } else {
                APP::Render('users/errors', 'include', 'auth_google_access_token');
            }
        } else {
            APP::Render('users/errors', 'include', 'auth_google_code');
        }
    }

    public function LoginYA() {
        if (!(int) $this->settings['module_users_login_service']) APP::Render('users/errors', 'include', 'auth_service');

        if (isset(APP::Module('Routing')->get['code'])) {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'https://oauth.yandex.ru/token');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query(['client_id' => $this->settings['module_users_oauth_client_ya_id'], 'client_secret' => $this->settings['module_users_oauth_client_ya_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/ya', 'grant_type' => 'authorization_code'])));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $ya_result = json_decode(curl_exec($curl), true);

            curl_close($curl);

            if (isset($ya_result['access_token'])) {
                $ya_user = json_decode(file_get_contents('https://login.yandex.ru/info?' . urldecode(http_build_query(['oauth_token' => $ya_result['access_token'], 'format' => 'json']))), true);

                if (isset($ya_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                        ['user_id'], 'users_accounts',
                        [
                            ['service', '=', 'ya', PDO::PARAM_STR],
                            ['extra', '=', $ya_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($ya_user['default_email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->settings['module_users_db_connection'], ['fetchColumn', 0],
                                ['id'], 'users', [['email', '=', $ya_user['default_email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"ya"',
                                        'extra' => [$ya_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) $this->settings['module_users_gen_pass_length']);
                                $user_id = $this->Register($ya_user['default_email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->settings['module_users_db_connection'], ' users_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'service' => '"ya"',
                                        'extra' => [$ya_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                APP::Module('Mail')->Send($ya_user['default_email'], $this->settings['module_users_register_letter'], [
                                    'email' => $ya_user['default_email'],
                                    'password' => $password
                                ]);
                            }
                        } else {
                            APP::Render('users/errors', 'include', 'auth_ya_email');
                        }
                    }
                } else {
                    APP::Render('users/errors', 'include', 'auth_ya_id');
                }

                header('Location: ' . APP::Module('Crypt')->Decode(json_decode(APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['state']), 1)['return']));
                exit;
            } else {
                APP::Render('users/errors', 'include', 'auth_ya_access_token');
            }
        } else {
            APP::Render('users/errors', 'include', 'auth_ya_code');
        }
    }
    
    
    public function RenderUnsubscribeShortcode($id, $data) {
        $unsubscribe_link = APP::Module('Routing')->root . 'users/unsubscribe/[letter_hash]';

        $data['letter']['html'] = str_replace('[unsubscribe-link]', $unsubscribe_link, $data['letter']['html']);
        $data['letter']['plaintext'] = str_replace('[unsubscribe-link]', $unsubscribe_link, $data['letter']['plaintext']);
        
        return $data;
    }
    
    public function Unsubscribe() {
        
        $mail_log = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['mail_log_hash']);
        
        if (!APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        )) {
            APP::Render(
                'users/unsubscribe', 'include', 
                [
                    'error' => true,
                ]
            );
            exit;
        }

        $user_id = APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['user'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        );
        
        APP::Render(
            'users/unsubscribe', 
            'include', 
            [
                'error' => false,
                'active' => APP::Module('DB')->Select(
                    $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'users_about',
                    [
                        ['user', '=', $user_id, PDO::PARAM_INT],
                        ['item', '=', 'state', PDO::PARAM_STR],
                        ['value', '=', 'active', PDO::PARAM_STR]
                    ]
                )
            ]
        );
    }
    
    public function APIUnsubscribe() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        $mail_log = APP::Module('Crypt')->Decode($_POST['mail_log']);
        
        if (!APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        )) {
            echo json_encode([
                'status' => 'error',
                'errors' => [1]
            ]);
            exit;
        }

        $mail = APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['user', 'letter'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        );

        if (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['value'], 'users_about',
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        ) != 'active') {
            echo json_encode([
                'status' => 'success',
                'errors' => []
            ]);
            exit;
        }
        
        APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users_tags',
            [
                'id' => 'NULL',
                'user' => [$mail['user'], PDO::PARAM_INT],
                'item' => ['unsubscribe', PDO::PARAM_STR],
                'value' => [json_encode([
                    'item' => 'mail',
                    'id' => $mail_log
                ]), PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('DB')->Update(
            $this->settings['module_users_db_connection'], 'users_about', 
            [
                'value' => 'inactive'
            ], 
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        );
        
        APP::Module('DB')->Insert(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_events',
            [
                'id' => 'NULL',
                'log' => [$mail_log, PDO::PARAM_INT],
                'user' => [$mail['user'], PDO::PARAM_INT],
                'letter' => [$mail['letter'], PDO::PARAM_INT],
                'event' => ['unsubscribe', PDO::PARAM_STR],
                'details' => 'NULL',
                'token' => [$mail_log, PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('Mail')->Send(
            APP::Module('DB')->Select(
                $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['email'], 'users',
                [['id', '=', $mail['user'], PDO::PARAM_INT]]
            ),
            $this->settings['module_users_subscription_restore_letter']
        );
        
        APP::Module('Triggers')->Exec('user_unsubscribe', [
            'user' => $mail['user'],
            'label' => 'unsubscribe'
        ]);

        echo json_encode([
            'status' => 'success',
            'errors' => []
        ]);
        exit;
    }
    
    public function APIPause() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        $mail_log = APP::Module('Crypt')->Decode($_POST['mail_log']);
        
        if (!APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        )) {
            echo json_encode([
                'status' => 'error',
                'errors' => [1]
            ]);
            exit;
        }

        $mail = APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['user', 'letter'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        );

        if (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['value'], 'users_about',
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        ) != 'active') {
            echo json_encode([
                'status' => 'success',
                'errors' => []
            ]);
            exit;
        }
        
        APP::Module('TaskManager')->Add(
            'Users', 'ActivateUserTask', 
            date('Y-m-d H:i:s', strtotime($_POST['timeout'])), 
            json_encode([$mail['user']]), 
            'activate_user', 
            'wait'
        );
        
        APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users_tags',
            [
                'id' => 'NULL',
                'user' => [$mail['user'], PDO::PARAM_INT],
                'item' => ['pause', PDO::PARAM_STR],
                'value' => [json_encode([
                    'item' => 'mail',
                    'id' => $mail_log,
                    'timeout' => $_POST['timeout']
                ]), PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('DB')->Update(
            $this->settings['module_users_db_connection'], 'users_about', 
            [
                'value' => 'inactive'
            ], 
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        );
        
        APP::Module('DB')->Insert(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_events',
            [
                'id' => 'NULL',
                'log' => [$mail_log, PDO::PARAM_INT],
                'user' => [$mail['user'], PDO::PARAM_INT],
                'letter' => [$mail['letter'], PDO::PARAM_INT],
                'event' => ['pause', PDO::PARAM_STR],
                'details' => 'NULL',
                'token' => [$mail_log, PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );

        APP::Module('Triggers')->Exec('user_pause', [
            'user' => $mail['user'],
            'label' => 'pause'
        ]);

        echo json_encode([
            'status' => 'success',
            'errors' => []
        ]);
        exit;
    }
    
    
    public function RenderRestoreUserShortcode($id, $data) {
        $user_email_hash = isset($data['params']['recepient']) ? APP::Module('Crypt')->Encode($data['params']['recepient']) : '[user_email]';
        $unsubscribe_link = APP::Module('Routing')->root . 'users/restore/' . $user_email_hash;

        $data['letter']['html'] = str_replace('[restore-user-link]', $unsubscribe_link, $data['letter']['html']);
        $data['letter']['plaintext'] = str_replace('[restore-user-link]', $unsubscribe_link, $data['letter']['plaintext']);
        
        return $data;
    }
    
    public function Restore() {
        $user_email = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_email_hash']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'users',
            [['email', '=', $user_email, PDO::PARAM_STR]]
        )) {
            APP::Render('users/restore', 'include', false);
            exit;
        }

        $user_id = APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['email', '=', $user_email, PDO::PARAM_STR]]
        );

        if (APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['value'], 'users_about',
            [
                ['user', '=', $user_id, PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        ) == 'active') {
            APP::Render('users/restore', 'include', true);
            exit;
        }
        
        APP::Module('DB')->Insert(
            $this->settings['module_users_db_connection'], 'users_tags',
            [
                'id' => 'NULL',
                'user' => [$user_id, PDO::PARAM_INT],
                'item' => ['restore', PDO::PARAM_STR],
                'value' => 'NULL',
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('DB')->Update(
            $this->settings['module_users_db_connection'], 'users_about', 
            [
                'value' => 'active'
            ], 
            [
                ['user', '=', $user_id, PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        );

        APP::Module('Triggers')->Exec('user_restore', [
            'user' => $user_id
        ]);
        
        APP::Render('users/restore', 'include', true);
    }
    
    
    public function ActivateUserTask($user_id) {
        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'users_about',
            [
                ['user', '=', $user_id, PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR],
                ['value', '=', 'active', PDO::PARAM_STR]
            ]
        )) {
            APP::Module('DB')->Update(
                $this->settings['module_users_db_connection'], 'users_about', 
                [
                    'value' => 'active'
                ], 
                [
                    ['user', '=', $user_id, PDO::PARAM_INT],
                    ['item', '=', 'state', PDO::PARAM_STR]
                ]
            );
            
            APP::Module('DB')->Insert(
                $this->settings['module_users_db_connection'], 'users_tags',
                [
                    'id' => 'NULL',
                    'user' => [$user_id, PDO::PARAM_INT],
                    'item' => ['change_state', PDO::PARAM_STR],
                    'value' => ['active', PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                ]
            );
        }
    }
    
}

class UsersSearch {

    public function id($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['id', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function email($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['email', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function role($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['role', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function state($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['item', '=', 'state', PDO::PARAM_STR],
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR]
            ]
        );
    }
    
    public function source($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['item', '=', 'source', PDO::PARAM_STR],
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR]
            ]
        );
    }
    
    public function firstname($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['item', '=', 'firstname', PDO::PARAM_STR]
            ]
        );
    }
    
    public function lastname($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['item', '=', 'lastname', PDO::PARAM_STR]
            ]
        );
    }
    
    public function tel($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['item', 'IN', ['tel', 'mobile_phone'], PDO::PARAM_STR]
            ]
        );
    }
    
    public function city($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['item', '=', 'city_name_ru', PDO::PARAM_STR]
            ]
        );
    }
    
    public function country($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['value', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['item', '=', 'country_name_ru', PDO::PARAM_STR]
            ]
        );
    }
    
    public function reg_date($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [
                ['reg_date', 'BETWEEN', '"' . $settings['date_from'] . ' 00:00:00" AND "' . $settings['date_to'] . ' 23:59:59"', PDO::PARAM_STR]
            ]
        );
    }
    
    public function utm($settings) {
        $where = [
            ['value', '=', $settings['value'], PDO::PARAM_STR],
            ['item', '=', $settings['item'], PDO::PARAM_STR]
        ];
        
        if((int)$settings['num']){
            $where[] = ['num', '=', $settings['num'], PDO::PARAM_INT];
        }
        
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_utm', $where
        );
    }
    
    public function social_id($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user_id'], 'users_accounts',
            [
                ['service', '=', $settings['service'], PDO::PARAM_STR],
                ['extra', $settings['logic'], $settings['value'], PDO::PARAM_STR]
            ]
        );
    }

    public function tags($settings) {
        switch ($settings['logic']) {
            case 'exist':
                return APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['user'], 'users_tags',
                    [
                        ['item', '=', $settings['value'], PDO::PARAM_STR],
                        ['cr_date', 'BETWEEN', '"' . $settings['date_from'] . ' 00:00:00" AND "' . $settings['date_to'] . ' 23:59:59"', PDO::PARAM_STR]
                    ]
                );
                break;
            case 'not_exist':

                $u_id = APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['user'], 'users_tags',
                    [
                        ['item', '=', $settings['value'], PDO::PARAM_STR],
                        ['cr_date', 'BETWEEN', '"' . $settings['date_from'] . ' 00:00:00" AND "' . $settings['date_to'] . ' 23:59:59"', PDO::PARAM_STR]
                    ]
                );
                
                return APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['user'], 'users_tags',
                    [
                        ['user', 'NOT IN', $u_id, PDO::PARAM_INT],
                        ['cr_date', 'BETWEEN', '"' . $settings['date_from'] . ' 00:00:00" AND "' . $settings['date_to'] . ' 23:59:59"', PDO::PARAM_STR]
                    ]
                );
                break;
        }
    }
    
    public function tunnels($settings) {
        switch ($settings['logic']) {
            case 'exist':
                return APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['tunnels_users.user_id'], 'tunnels_users',
                    [['tunnel_id', '=', $settings['value'], PDO::PARAM_INT]]
                );
                break;

            case 'not_exist':
                $u_id = APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['user_id'], 'tunnels_users',
                    [['tunnels_users.tunnel_id', '=', $settings['value'], PDO::PARAM_INT]]
                );
                
                return APP::Module('DB')->Select(
                    APP::Module('Users')->settings['module_users_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['users.id'], 'users',
                    [['users.id', 'NOT IN', $u_id, PDO::PARAM_INT]]
                );
                break;

            case 'active' : 
                return APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['tunnels_users.user_id'], 'tunnels_users',
                    [
                        ['tunnel_id', '=', $settings['value'], PDO::PARAM_INT],
                        ['state', '=', 'active', PDO::PARAM_STR]
                    ]
                );
                break;

            case 'complete':
                return APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['tunnels_users.user_id'], 'tunnels_users',
                    [
                        ['tunnel_id', '=', $settings['value'], PDO::PARAM_INT],
                        ['state', '=', 'complete', PDO::PARAM_STR]
                    ]
                );
                break;

            case 'pause':
                return APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN], 
                    ['tunnels_users.user_id'], 'tunnels_users',
                    [
                        ['tunnel_id', '=', $settings['value'], PDO::PARAM_INT],
                        ['state', '=', 'pause', PDO::PARAM_STR]
                    ]
                );
                break;
        }
    }
    
    public function tunnels_type($settings) {       
        $users = APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['tunnels_users.user_id'], 'tunnels_users',
            [
                ['tunnels_users.state', '=', 'active', PDO::PARAM_INT],
                ['tunnels.type', '=', $settings['value'], PDO::PARAM_INT]
            ],
            [
                'join/tunnels' => [
                    ['tunnels.id', '=', 'tunnels_users.tunnel_id']
                ]
            ]
        );
        
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['item', '=', 'state', PDO::PARAM_STR],
                ['value', '=', 'active', PDO::PARAM_STR],
                ['user', $settings['logic'], $users, PDO::PARAM_INT]
            ]
        );
    }
    
    public function tunnels_tags($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['DISTINCT tunnels_users.user_id'], 'tunnels_tags',
            [
                ['tunnels_tags.token', '=', $settings['token'], PDO::PARAM_STR],
                ['tunnels_tags.label_id', '=', $settings['label'], PDO::PARAM_STR]
            ],
            [
                'join/tunnels_users' => [
                    ['tunnels_users.id', '=', 'tunnels_tags.user_tunnel_id']
                ]
            ],
            false,
            false,
            ['tunnels_tags.cr_date', 'DESC']
        );
    }
    
    public function tunnels_queue($settings) {
        $users_in_queue = APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['user_id'], 'tunnels_queue'
        );
        
        return APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user'], 'users_about',
            [
                ['item', '=', 'state', PDO::PARAM_STR],
                ['value', '=', 'active', PDO::PARAM_STR],
                ['user', $settings['logic'], $users_in_queue, PDO::PARAM_INT]
            ]
        );
    }
    
    public function tunnels_object($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['user_id'], 'tunnels_users',
            [
                ['tunnel_id', '=', $settings['value'], PDO::PARAM_INT],
                ['object', '=', $settings['object'], PDO::PARAM_INT]
            ]
        );
    }
    
    public function tunnels_label($settings) {
        $settings['label_data'] = isset($settings['label_data']) ? $settings['label_data'] : '';

        if(isset($settings['from']) || isset($settings['to'])){
            if(!isset($settings['from'])){
                $date_from = APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN],
                    ['UNIX_TIMESTAMP(cr_date)'], 
                    'tunnels_tags',
                    false,
                    false,
                    false,
                    false,
                    ['cr_date', 'ASC']
                );
            }

            $date_range = [
                'from' => (isset($settings['from']) ? $settings['from'] : date('Y-m-d', $date_from)). ' 00:00:00',
                'to' => (isset($settings['to']) ? $settings['to'] : date('Y-m-d', time())) . ' 23:59:59'
            ];
        }

        switch ($settings['mode']) {
            case 'exist':
                $timeout = 0;
                $cr_date = 0;
                $where = false;
                $join = false;

                if (isset($settings['timeout'])) {
                    switch ($settings['timeout']['mode']) {
                        case 'min': $timeout = (int) $settings['timeout']['value'] * 60; break;
                        case 'hours': $timeout = (int) $settings['timeout']['value'] * 3600; break;
                        case 'days': $timeout = (int) $settings['timeout']['value'] * 86400; break;
                        default: $timeout = (int) $settings['timeout']['value'];
                    }
                }

                if (isset($settings['cr_date_mode'])) {
                    switch ($settings['cr_date_mode']) {
                        case 'min': $cr_date = (int) $settings['cr_date_value'] * 60; break;
                        case 'hours': $cr_date = (int) $settings['cr_date_value'] * 3600; break;
                        case 'days': $cr_date = (int) $settings['cr_date_value'] * 86400; break;
                        default: $cr_date = (int) $settings['cr_date_value'];
                    }
                }

                if(isset($settings['label_id'])){
                    $where[] = ['label_id', '=', $settings['label_id'], PDO::PARAM_STR];
                }

                if($timeout){
                    $where[] = ['UNIX_TIMESTAMP(cr_date)', '<=', (time() - $timeout), PDO::PARAM_STR];
                }

                if ($cr_date) {
                    $where[] = ['cr_date', 'BETWEEN', '"' . date('Y-m-d H:i:s', (time() - $cr_date)) . '" AND "' . date('Y-m-d H:i:s',time()) . '"', PDO::PARAM_STR];
                }

                if (isset($date_range)) {
                    $where[] = ['cr_date', 'BETWEEN', '"' . $date_range['from'] . '" AND "' . $date_range['to'] . '"', PDO::PARAM_STR];
                }
                
                if (isset($settings['token'])) {
                    $where[] = ['token', '=', $settings['token'], PDO::PARAM_STR];
                }


                $join['join/tunnels_users'][] = ['tunnels_users.id', '=', 'tunnels_tags.user_tunnel_id'];

                if (isset($settings['tunnel_id'])) {
                    $join['join/tunnels_users'][] = ['tunnels_users.tunnel_id', '=', $settings['tunnel_id']];
                }

                return  APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], 
                    ['fetchAll', PDO::FETCH_COLUMN],
                    ['DISTINCT tunnels_users.user_id'], 
                    'tunnels_tags',
                    $where,
                    $join,
                    false,
                    false,
                    ['cr_date', 'DESC']
                );
                break;
            case 'not_exist': 
                return [];
                break;
        }
    }
    
    public function mail_count($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN],
            ['user'], 'mail_log',
            false,
            false,
            ['user'],
            [['COUNT(id)', $settings['logic'], $settings['value']]]
        );
    }
    
    public function mail_events($settings) {  
        $where[] = ['mail_log.state', '=', 'success', PDO::PARAM_INT];
        
        if(isset($settings['letter_id']) && count($settings['letter_id'])){
            $where[] = ['mail_log.letter', 'IN', $settings['letter_id']];
        }

        return APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN],
            ['mail_log.user'], 'mail_log',
            [
                ['mail_events.event', $settings['logic'], $settings['value'], PDO::PARAM_STR],
                ['mail_log.state', '=', 'success', PDO::PARAM_STR],
                ['mail_log.cr_date', 'BETWEEN', '"' . $settings['date_from'] . ' 00:00:00" AND "' . $settings['date_to'] . ' 23:59:59"', PDO::PARAM_STR]
                
            ],
            [
                'join/mail_events' => [
                    ['mail_events.log', '=', 'mail_log.id']
                ]
            ],
            ['mail_log.user']
        );
    }
    
    public function mail_open_pct($settings) {

        if (($settings['from'] == 0) && ($settings['to'] == 100))  return []; 
        
        return APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN],
            ['user'], 'mail_open_pct',
            [['pct', 'BETWEEN', '"'.$settings['from'] . '" AND "' . $settings['to'].'"', PDO::PARAM_INT]]
        );
    }
    
    public function mail_open_pct30($settings) {

        if (($settings['from'] == 0) && ($settings['to'] == 100))  return []; 
        
        return APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN],
            ['user'], 'mail_open_pct30',
            [['pct', 'BETWEEN', '"'.$settings['from'] . '" AND "' . $settings['to'].'"', PDO::PARAM_INT]]
        );
    }
}

class UsersActions {

    public function remove($id, $settings) {
        return APP::Module('DB')->Delete(APP::Module('Users')->settings['module_users_db_connection'], 'users', [['id', 'IN', $id]]);
    }
    
    public function tunnel_subscribe($id, $settings){
        foreach (APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['email'], 'users',
            [['id', 'IN', $id, PDO::PARAM_INT]]
        ) as $email) {
            $out = APP::Module('Tunnels')->Subscribe([
                'email'             => $email,
                'tunnel'            => $settings['tunnel'],
                'activation'        => $settings['activation'],
                'source'            => isset($settings['source']) && $settings['source'] ? substr($settings['source'], 0, 100) : 'APISubscribe',
                'roles_tunnel'      => isset($settings['roles_tunnel']) ? $settings['roles_tunnel'] : false,
                'welcome'           => isset($settings['welcome']) && $settings['welcome'][0] ? $settings['welcome'] : false,
                'queue_timeout'     => isset($settings['queue_timeout']) ? $settings['queue_timeout'] : APP::Module('Tunnels')->settings['module_tunnels_default_queue_timeout'],
                'complete_tunnels'  => isset($settings['complete_tunnels']) && count($settings['complete_tunnels']) ? $settings['complete_tunnels'] : false,
                'pause_tunnels'     => isset($settings['pause_tunnels']) && count($settings['pause_tunnels']) ? $settings['pause_tunnels'] : false,
                'input_data'        => isset($settings['input_data']) ? $settings['input_data'] : [],
                'about_user'        => isset($settings['about_user']) ? $settings['about_user'] : [],
                'auto_save_about'   => isset($settings['auto_save_about']) ? $settings['auto_save_about'] : false,
                'save_utm'          => isset($settings['save_utm']) ? $settings['save_utm'] : false
            ]);
            
            if (isset($result['status']) && $result['status'] == 'error') {
                $out['status'] = 'error';
                $out['code'][] = $result['code'];
            }
        }
        
        return $out;
    }
}
