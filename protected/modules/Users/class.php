<?
class Users {

    public $user = Array();
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->user = &APP::Module('Sessions')->session['modules']['users']['user'];
        
        if (!isset(APP::Module('Sessions')->session['modules']['users']['double_auth'])) {
            APP::Module('Sessions')->session['modules']['users']['double_auth'] = false;
        }
        
        if (!$this->user) {
            $this->user['role'] = 'default';
        }

        if (isset($_COOKIE['modules']['users']['token'])) {
            if ($user = $this->Login($_COOKIE['modules']['users']['email'], $_COOKIE['modules']['users']['token'])) {
                $this->user = $this->Auth($user);
            }
        }
        
        if (((int) APP::Module('Registry')->Get('module_users_auth_token')) && ((int) APP::Module('Registry')->Get('module_users_login_service'))) {
            if (isset(APP::Module('Routing')->get['user_token'])) {
                $token = json_decode(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['user_token']), 1);

                if ($user = $this->Login($token[0], $token[1])) {
                    $this->user = $this->Auth($user, true, true);
                }
            }
        }

        if ((int) APP::Module('Registry')->Get('module_users_check_rules')) {
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

    public function Admin() {
        return APP::Render('users/admin/nav', 'content');
    }
    
    
    public function Login($email, $password) {
        return (int) APP::Module('DB')->Select(
            $this->conf['connection'], ['fetchColumn', 0], ['id'], 'users',
            [
                ['email', '=', $email, PDO::PARAM_STR],
                ['password', '=', $password, PDO::PARAM_STR]
            ]
        );
    }
    
    public function Register($email, $password, $role = 'new') {
        return APP::Module('DB')->Insert(
            $this->conf['connection'], 'users',
            Array(
                'id'            => 'NULL',
                'email'         => [$email, PDO::PARAM_STR],
                'password'      => [APP::Module('Crypt')->Encode($password), PDO::PARAM_STR],
                'role'          => [$role, PDO::PARAM_STR],
                'reg_date'      => 'NOW()',
                'last_visit'    => 'NOW()',
            )
        );
    }
    
    public function Auth($id, $set_cookie = true, $save_password = false) {
        $user = APP::Module('DB')->Select($this->conf['connection'], ['fetch', PDO::FETCH_ASSOC], ['*'], 'users', [['id', '=', $id, PDO::PARAM_INT]]);
        
        if ($set_cookie) {
            setcookie(
                'modules[users][email]', 
                $user['email'], 
                strtotime('+' . APP::Module('Registry')->Get('module_users_timeout_email')), 
                APP::$conf['location'][2],
                APP::$conf['location'][1]
            );

            setcookie(
                'modules[users][token]', 
                APP::Module('Crypt')->Encode($user['password']), 
                $save_password ? strtotime('+' . APP::Module('Registry')->Get('module_users_timeout_token')) : 0, 
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
    
    public function GeneratePassword($number) {
        $chars = [
            'a','b','c','d','e','f',
            'g','h','i','j','k','l',
            'm','n','o','p','r','s',
            't','u','v','x','y','z',
            'A','B','C','D','E','F',
            'G','H','I','J','K','L',
            'M','N','O','P','R','S',
            'T','U','V','X','Y','Z',

            '1','2','3','4','5','6',
            '7','8','9','0'

            /*
            '.',',',
            '(',')','[',']','!','?',
            '&','^','%','@','*','$',
            '<','>','/','|','+','-',
            '{','}','`','~'
            */
        ];

        $pass = '';
        
        for($i = 0; $i < $number; $i++) {
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
                strtotime('-' . APP::Module('Registry')->Get('module_users_timeout_email')), 
                APP::$conf['location'][2], APP::$conf['location'][1]
            );
        }
        
        setcookie(
            'modules[users][token]', '', 
            strtotime('-' . APP::Module('Registry')->Get('module_users_timeout_token')), 
            APP::$conf['location'][2], APP::$conf['location'][1]
        );
        
        $this->user = false;
        
        header('Location: ' . APP::Module('Routing')->root);
        exit;
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
                    $this->conf['connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
                    [['id', '=', $user_id, PDO::PARAM_INT]]
                ),
                'roles' => APP::Module('Registry')->Get(['module_users_role'])['module_users_role']
            ]
        );
    }
    
    public function Actions() {
        $return = isset(APP::Module('Routing')->get['return']) ? APP::Module('Crypt')->SafeB64Decode(APP::Module('Routing')->get['return']) : false;

        APP::Render(
            'users/actions', 'include', 
            [
                'return' => $return ? filter_var($return, FILTER_VALIDATE_URL) ? $return : false : false,
                'social_networks' => APP::Module('Registry')->Get([
                    'module_users_social_auth_vk_id',
                    'module_users_social_auth_fb_id',
                    'module_users_social_auth_google_id',
                    'module_users_social_auth_ya_id'
                ])
            ]
        );
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
        
        if (APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['id'], 'users', [['id', '=', $user_id, PDO::PARAM_INT]])) {
            APP::Module('DB')->Update(
                $this->conf['connection'], 'users', 
                ['role' => 'user'], 
                [
                    ['id', '=', $user_id, PDO::PARAM_INT],
                    ['role', '!=', 'user', PDO::PARAM_STR]
                ]
            );
            
            APP::Module('Triggers')->Exec('user_activate', ['user_id' => $user_id]);
        } else {
            $result = 'error';
        }
        
        APP::Render('users/activate', 'include', $result);
    }
    
    public function Profile() {
        APP::Render(
            'users/profile', 'include', 
            [
                'social-profiles' => APP::Module('DB')->Select(
                    $this->conf['connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    ['network', 'extra'], 'social_accounts',
                    [['user_id', '=', $this->user['id'], PDO::PARAM_INT]]
                )
            ]
        );
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
        $prefix = 'module_users_social_auth_';
        
        APP::Render(
            'users/admin/oauth_clients', 'include', 
            APP::Module('Registry')->Get([
                $prefix . 'fb_id',
                $prefix . 'fb_key',
                $prefix . 'vk_id',
                $prefix . 'vk_key',
                $prefix . 'google_id',
                $prefix . 'google_key',
                $prefix . 'ya_id',
                $prefix . 'ya_key'
            ])
        );
    }
    
    public function SetupNotifications() {
        APP::Render(
            'users/admin/notifications', 'include', 
            APP::Module('Registry')->Get([
                'module_users_register_activation_letter',
                'module_users_reset_password_letter',
                'module_users_register_letter',
                'module_users_change_password_letter'
            ])
        );
    }
    
    public function SetupServices() {
        APP::Render(
            'users/admin/services', 'include', 
            APP::Module('Registry')->Get([
                'module_users_login_service',
                'module_users_register_service',
                'module_users_reset_password_service',
                'module_users_change_password_service'
            ])
        );
    }
    
    public function SetupAuth() {
        APP::Render(
            'users/admin/auth', 'include', 
            APP::Module('Registry')->Get([
                'module_users_check_rules',
                'module_users_auth_token'
            ])
        );
    }
    
    public function SetupPasswords() {
        APP::Render(
            'users/admin/passwords', 'include', 
            APP::Module('Registry')->Get([
                'module_users_min_pass_length',
                'module_users_gen_pass_length'
            ])
        );
    }
    
    public function SetupTimeouts() {
        APP::Render(
            'users/admin/timeouts', 'include', 
            APP::Module('Registry')->Get([
                'module_users_timeout_token',
                'module_users_timeout_email',
                'module_users_timeout_activation'
            ])
        );
    }
    
    
    public function APIListUsers() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->conf['connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'email', 'password', 'role', 'reg_date', 'last_visit'], 'users',
            $_POST['searchPhrase'] ? [['email', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            false, false, false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
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
            'total' => APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', $_POST['searchPhrase'] ? [['email', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIRemoveUser() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->conf['connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'users',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->conf['connection'], 'users',
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
        } else if (APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if (strlen($_POST['password']) < (int) APP::Module('Registry')->Get('module_users_min_pass_length')) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if ($out['status'] == 'success') {
            $user_id = $this->Register($_POST['email'], $_POST['password'], $_POST['role']);
            
            if ((int) $_POST['notification']) {
                $letter = APP::Module('DB')->Select(
                    APP::Module('Mail')->conf['connection'], 
                    ['fetch', PDO::FETCH_ASSOC], 
                    [
                        'letters.subject', 
                        'letters.html', 
                        'letters.plaintext', 
                        'letters.list_id',
                        'senders.name',
                        'senders.email'
                    ], 
                    'letters', 
                    [['letters.id', '=', $_POST['notification'], PDO::PARAM_INT]],
                    ['join/senders' => [['senders.id','=','letters.sender_id']]]
                );

                $login_details = [
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'expire' => strtotime('+' . APP::Module('Registry')->Get('module_users_timeout_activation')),
                    'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user_id)
                ];

                APP::Module('Mail')->Send(
                    [$letter['email'], $letter['name']], $_POST['email'], $letter['subject'], 
                    [
                        APP::Render($letter['html'], 'eval', $login_details),
                        APP::Render($letter['plaintext'], 'eval', $login_details)
                    ],
                    ['List-id' => $letter['list_id']]
                );
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
        
        if (($user = $this->Login($_POST['email'], APP::Module('Crypt')->Encode($_POST['password']))) && ((int) APP::Module('Registry')->Get('module_users_login_service'))) {
            $this->user = $this->Auth($user, true, isset($_POST['remember-me']));
            $status = 'success';
            
            APP::Module('Triggers')->Exec('user_login', [
                'id' => $user,
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'remember-me' => $_POST['remember-me']
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
        } else if (APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if (strlen($_POST['password']) < (int) APP::Module('Registry')->Get('module_users_min_pass_length')) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (!(int) APP::Module('Registry')->Get('module_users_register_service')) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if ($out['status'] == 'success') {
            $user_id = $this->Register($_POST['email'], $_POST['password']);
            $this->user = $this->Auth($user_id, true, false);

            $letter = APP::Module('DB')->Select(
                APP::Module('Mail')->conf['connection'], 
                ['fetch', PDO::FETCH_ASSOC], 
                [
                    'letters.subject', 
                    'letters.html', 
                    'letters.plaintext', 
                    'letters.list_id',
                    'senders.name',
                    'senders.email'
                ], 
                'letters', 
                [['letters.id', '=', APP::Module('Registry')->Get('module_users_register_activation_letter'), PDO::PARAM_INT]],
                ['join/senders' => [['senders.id','=','letters.sender_id']]]
            );

            $login_details = [
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'expire' => strtotime('+' . APP::Module('Registry')->Get('module_users_timeout_activation')),
                'link' => APP::Module('Routing')->root . 'users/activate/' . APP::Module('Crypt')->Encode($user_id)
            ];
            
            APP::Module('Mail')->Send(
                [$letter['email'], $letter['name']], $_POST['email'], $letter['subject'], 
                [
                    APP::Render($letter['html'], 'eval', $login_details),
                    APP::Render($letter['plaintext'], 'eval', $login_details)
                ],
                ['List-id' => $letter['list_id']]
            );
            
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
    
    public function APIResetPassword() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        } else if (!APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['id'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (!(int) APP::Module('Registry')->Get('module_users_reset_password_service')) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if ($out['status'] == 'success') {
            $token = [
                $_POST['email'],
                APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['password'], 'users', [['email', '=', $_POST['email'], PDO::PARAM_STR]])
            ];

            $letter = APP::Module('DB')->Select(
                APP::Module('Mail')->conf['connection'], 
                ['fetch', PDO::FETCH_ASSOC], 
                [
                    'letters.subject', 
                    'letters.html', 
                    'letters.plaintext', 
                    'letters.list_id',
                    'senders.name',
                    'senders.email'
                ], 
                'letters', 
                [['letters.id', '=', APP::Module('Registry')->Get('module_users_reset_password_letter'), PDO::PARAM_INT]],
                ['join/senders' => [['senders.id','=','letters.sender_id']]]
            );

            $reset_password_details = ['link' => APP::Module('Routing')->root . 'users/actions/change-password?user_token=' . APP::Module('Crypt')->Encode(json_encode($token))];
            
            $out = [
                'status' => 'success',
                'info' => APP::Module('Mail')->Send(
                    [$letter['email'], $letter['name']], $_POST['email'], $letter['subject'], 
                    [
                        APP::Render($letter['html'], 'eval', $reset_password_details),
                        APP::Render($letter['plaintext'], 'eval', $reset_password_details)
                    ],
                    ['List-id' => $letter['list_id']]
                )
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
        } else if (strlen($_POST['password']) < (int) APP::Module('Registry')->Get('module_users_min_pass_length')) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        } else if ($_POST['password'] != $_POST['re-password']) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (!(int) APP::Module('Registry')->Get('module_users_change_password_service')) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }

        if ($out['status'] == 'success') {
            APP::Module('Triggers')->Exec('change_user_password', [
                'user' => $this->user,
                'password' => $_POST['password']
            ]);
            
            APP::Module('DB')->Update(
                $this->conf['connection'], 'users', 
                ['password' => APP::Module('Crypt')->Encode($_POST['password'])], 
                [['id', '=', $this->user['id'], PDO::PARAM_INT]]
            );
            
            $this->user = $this->Auth($this->user['id'], true, true);

            $letter = APP::Module('DB')->Select(
                APP::Module('Mail')->conf['connection'], 
                ['fetch', PDO::FETCH_ASSOC], 
                [
                    'letters.subject', 
                    'letters.html', 
                    'letters.plaintext', 
                    'letters.list_id',
                    'senders.name',
                    'senders.email'
                ], 
                'letters', 
                [['letters.id', '=', APP::Module('Registry')->Get('module_users_change_password_letter'), PDO::PARAM_INT]],
                ['join/senders' => [['senders.id','=','letters.sender_id']]]
            );

            $change_password_details = [
                'email' => $this->user['email'],
                'password' => $_POST['password']
            ];
            
            $out = [
                'status' => 'success',
                'info' => APP::Module('Mail')->Send(
                    [$letter['email'], $letter['name']], $this->user['email'], $letter['subject'], 
                    [
                        APP::Render($letter['html'], 'eval', $change_password_details),
                        APP::Render($letter['plaintext'], 'eval', $change_password_details)
                    ],
                    ['List-id' => $letter['list_id']]
                )
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
                strtotime('-' . APP::Module('Registry')->Get('module_users_timeout_email')), 
                APP::$conf['location'][2], APP::$conf['location'][1]
            );
        }
        
        setcookie(
            'modules[users][token]', '', 
            strtotime('-' . APP::Module('Registry')->Get('module_users_timeout_token')), 
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

        if (!APP::Module('DB')->Select($this->conf['connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', $user_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
            if (empty($_POST['password'])) {
                $out['status'] = 'error';
                $out['errors'][] = 2;
            } else if (strlen($_POST['password']) < (int) APP::Module('Registry')->Get('module_users_min_pass_length')) {
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
            APP::Module('DB')->Update($this->conf['connection'], 'users', ['role' => $_POST['role']], [['id', '=', $user_id, PDO::PARAM_INT]]);
        
            if (APP::Module('Sessions')->session['modules']['users']['double_auth']) {
                APP::Module('DB')->Update($this->conf['connection'], 'users', ['password' => APP::Module('Crypt')->Encode($_POST['password'])], [['id', '=', $user_id, PDO::PARAM_INT]]);
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
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_fb_id']], [['item', '=', 'module_users_social_auth_fb_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_fb_key']], [['item', '=', 'module_users_social_auth_fb_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_vk_id']], [['item', '=', 'module_users_social_auth_vk_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_vk_key']], [['item', '=', 'module_users_social_auth_vk_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_google_id']], [['item', '=', 'module_users_social_auth_google_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_google_key']], [['item', '=', 'module_users_social_auth_google_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_ya_id']], [['item', '=', 'module_users_social_auth_ya_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_users_social_auth_ya_key']], [['item', '=', 'module_users_social_auth_ya_key', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_users_oauth_settings', [
            'social_auth_fb_id' => $_POST['module_users_social_auth_fb_id'],
            'social_auth_fb_key' => $_POST['module_users_social_auth_fb_key'],
            'social_auth_vk_id' => $_POST['module_users_social_auth_vk_id'],
            'social_auth_vk_key' => $_POST['module_users_social_auth_vk_key'],
            'social_auth_google_id' => $_POST['module_users_social_auth_google_id'],
            'social_auth_google_key' => $_POST['module_users_social_auth_google_key'],
            'social_auth_ya_id' => $_POST['module_users_social_auth_ya_id'],
            'social_auth_ya_key' => $_POST['module_users_social_auth_ya_key']
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
        
        APP::Module('Triggers')->Exec('update_users_notifications_settings', [
            'register_activation_letter' => $_POST['module_users_register_activation_letter'],
            'reset_password_letter' => $_POST['module_users_reset_password_letter'],
            'register_letter' => $_POST['module_users_register_letter'],
            'change_password_letter' => $_POST['module_users_change_password_letter']
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
            'login_service' => $_POST['module_users_login_service'],
            'register_service' => $_POST['module_users_register_service'],
            'reset_password_service' => $_POST['module_users_reset_password_service'],
            'change_password_service' => $_POST['module_users_change_password_service']
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
            'check_rules' => $_POST['module_users_check_rules'],
            'auth_token' => $_POST['module_users_auth_token']
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

    
    public function LoginVK() {
        if (!(int) APP::Module('Registry')->Get('module_users_login_service')) APP::Render('users/errors', 'include', 'auth_service');
        
        if (isset(APP::Module('Routing')->get['code'])) {
            $vk_auth = APP::Module('Registry')->Get([
                'module_users_social_auth_vk_id',
                'module_users_social_auth_vk_key'
            ]);

            $vk_result = json_decode(file_get_contents('https://oauth.vk.com/access_token?' . urldecode(http_build_query(['client_id' => $vk_auth['module_users_social_auth_vk_id'], 'client_secret' => $vk_auth['module_users_social_auth_vk_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/vk']))), true);

            if (isset($vk_result['user_id'])) {
                if ($user_id = APP::Module('DB')->Select(
                        $this->conf['connection'], ['fetchColumn', 0], 
                        ['user_id'], 'social_accounts', 
                        [
                            ['network', '=', 'vk', PDO::PARAM_STR], 
                            ['extra', '=', $vk_result['user_id'], PDO::PARAM_STR]
                        ]
                )) {
                    $this->user = $this->Auth($user_id, true, true);
                } else {
                    if (isset($vk_result['email'])) {
                        if ($user_id = APP::Module('DB')->Select(
                                $this->conf['connection'], ['fetchColumn', 0], 
                                ['id'], 'users', [['email', '=', $vk_result['email'], PDO::PARAM_STR]]
                        )) {
                            APP::Module('DB')->Insert(
                                $this->conf['connection'], ' social_accounts',
                                [
                                    'id' => 'NULL',
                                    'user_id' => [$user_id, PDO::PARAM_INT],
                                    'network' => '"vk"',
                                    'extra' => [$vk_result['user_id'], PDO::PARAM_STR],
                                    'up_date' => 'NOW()',
                                ]
                            );
                            
                            $this->user = $this->Auth($user_id, true, true);
                        } else {
                            $password = $this->GeneratePassword((int) APP::Module('Registry')->Get('module_users_gen_pass_length'));
                            $user_id = $this->Register($vk_result['email'], $password, 'user');
                            $this->user = $this->Auth($user_id, true, true);
                            
                            APP::Module('DB')->Insert(
                                $this->conf['connection'], ' social_accounts',
                                [
                                    'id' => 'NULL',
                                    'user_id' => [$user_id, PDO::PARAM_INT],
                                    'network' => '"vk"',
                                    'extra' => [$vk_result['user_id'], PDO::PARAM_STR],
                                    'up_date' => 'NOW()',
                                ]
                            );
                            
                            $letter = APP::Module('DB')->Select(
                                APP::Module('Mail')->conf['connection'], 
                                ['fetch', PDO::FETCH_ASSOC], 
                                [
                                    'letters.subject', 
                                    'letters.html', 
                                    'letters.plaintext', 
                                    'letters.list_id',
                                    'senders.name',
                                    'senders.email'
                                ], 
                                'letters', 
                                [['letters.id', '=', APP::Module('Registry')->Get('module_users_register_letter'), PDO::PARAM_INT]],
                                ['join/senders' => [['senders.id','=','letters.sender_id']]]
                            );

                            $login_details = [
                                'email' => $vk_result['email'],
                                'password' => $password,
                            ];

                            APP::Module('Mail')->Send(
                                [$letter['email'], $letter['name']], $vk_result['email'], $letter['subject'], 
                                [
                                    APP::Render($letter['html'], 'eval', $login_details),
                                    APP::Render($letter['plaintext'], 'eval', $login_details)
                                ],
                                ['List-id' => $letter['list_id']]
                            );
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
        if (!(int) APP::Module('Registry')->Get('module_users_login_service')) APP::Render('users/errors', 'include', 'auth_service');
        
        if (isset(APP::Module('Routing')->get['code'])) {
            $fb_auth = APP::Module('Registry')->Get([
                'module_users_social_auth_fb_id',
                'module_users_social_auth_fb_key'
            ]);

            $fb_result = null;
            
            parse_str(file_get_contents('https://graph.facebook.com/oauth/access_token?' . urldecode(http_build_query(['client_id' => $fb_auth['module_users_social_auth_fb_id'], 'client_secret' => $fb_auth['module_users_social_auth_fb_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/fb']))), $fb_result);

            if (count($fb_result) > 0 && isset($fb_result['access_token'])) {
                $fb_user = json_decode(file_get_contents('https://graph.facebook.com/me?fields=email&' . urldecode(http_build_query(array('access_token' => $fb_result['access_token'])))), true);
                
                if (isset($fb_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->conf['connection'], ['fetchColumn', 0], 
                        ['user_id'], 'social_accounts', 
                        [
                            ['network', '=', 'fb', PDO::PARAM_STR], 
                            ['extra', '=', $fb_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($fb_user['email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->conf['connection'], ['fetchColumn', 0], 
                                ['id'], 'users', [['email', '=', $fb_user['email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"fb"',
                                        'extra' => [$fb_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) APP::Module('Registry')->Get('module_users_gen_pass_length'));
                                $user_id = $this->Register($fb_user['email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"fb"',
                                        'extra' => [$fb_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $letter = APP::Module('DB')->Select(
                                    APP::Module('Mail')->conf['connection'], 
                                    ['fetch', PDO::FETCH_ASSOC], 
                                    [
                                        'letters.subject', 
                                        'letters.html', 
                                        'letters.plaintext', 
                                        'letters.list_id',
                                        'senders.name',
                                        'senders.email'
                                    ], 
                                    'letters', 
                                    [['letters.id', '=', APP::Module('Registry')->Get('module_users_register_letter'), PDO::PARAM_INT]],
                                    ['join/senders' => [['senders.id','=','letters.sender_id']]]
                                );

                                $login_details = [
                                    'email' => $fb_user['email'],
                                    'password' => $password,
                                ];

                                APP::Module('Mail')->Send(
                                    [$letter['email'], $letter['name']], $fb_user['email'], $letter['subject'], 
                                    [
                                        APP::Render($letter['html'], 'eval', $login_details),
                                        APP::Render($letter['plaintext'], 'eval', $login_details)
                                    ],
                                    ['List-id' => $letter['list_id']]
                                );
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
        if (!(int) APP::Module('Registry')->Get('module_users_login_service')) APP::Render('users/errors', 'include', 'auth_service');
        
        if (isset(APP::Module('Routing')->get['code'])) {
            $google_auth = APP::Module('Registry')->Get([
                'module_users_social_auth_google_id',
                'module_users_social_auth_google_key'
            ]);

            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query(['client_id' => $google_auth['module_users_social_auth_google_id'], 'client_secret' => $google_auth['module_users_social_auth_google_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/google', 'grant_type' => 'authorization_code'])));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            
            $google_result = json_decode(curl_exec($curl), true);
            
            curl_close($curl);

            if (isset($google_result['access_token'])) {
                $google_user = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query(['access_token' => $google_result['access_token']]))), true);

                if (isset($google_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->conf['connection'], ['fetchColumn', 0], 
                        ['user_id'], 'social_accounts', 
                        [
                            ['network', '=', 'google', PDO::PARAM_STR], 
                            ['extra', '=', $google_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($google_user['email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->conf['connection'], ['fetchColumn', 0], 
                                ['id'], 'users', [['email', '=', $google_user['email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"google"',
                                        'extra' => [$google_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) APP::Module('Registry')->Get('module_users_gen_pass_length'));
                                $user_id = $this->Register($google_user['email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"google"',
                                        'extra' => [$google_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $letter = APP::Module('DB')->Select(
                                    APP::Module('Mail')->conf['connection'], 
                                    ['fetch', PDO::FETCH_ASSOC], 
                                    [
                                        'letters.subject', 
                                        'letters.html', 
                                        'letters.plaintext', 
                                        'letters.list_id',
                                        'senders.name',
                                        'senders.email'
                                    ], 
                                    'letters', 
                                    [['letters.id', '=', APP::Module('Registry')->Get('module_users_register_letter'), PDO::PARAM_INT]],
                                    ['join/senders' => [['senders.id','=','letters.sender_id']]]
                                );

                                $login_details = [
                                    'email' => $google_user['email'],
                                    'password' => $password,
                                ];

                                APP::Module('Mail')->Send(
                                    [$letter['email'], $letter['name']], $google_user['email'], $letter['subject'], 
                                    [
                                        APP::Render($letter['html'], 'eval', $login_details),
                                        APP::Render($letter['plaintext'], 'eval', $login_details)
                                    ],
                                    ['List-id' => $letter['list_id']]
                                );
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
        if (!(int) APP::Module('Registry')->Get('module_users_login_service')) APP::Render('users/errors', 'include', 'auth_service');
        
        if (isset(APP::Module('Routing')->get['code'])) {
            $ya_auth = APP::Module('Registry')->Get([
                'module_users_social_auth_ya_id',
                'module_users_social_auth_ya_key'
            ]);

            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, 'https://oauth.yandex.ru/token');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query(['client_id' => $ya_auth['module_users_social_auth_ya_id'], 'client_secret' => $ya_auth['module_users_social_auth_ya_key'], 'code' => APP::Module('Routing')->get['code'], 'redirect_uri' => APP::Module('Routing')->root . 'users/login/ya', 'grant_type' => 'authorization_code'])));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            
            $ya_result = json_decode(curl_exec($curl), true);
            
            curl_close($curl);

            if (isset($ya_result['access_token'])) {
                $ya_user = json_decode(file_get_contents('https://login.yandex.ru/info?' . urldecode(http_build_query(['oauth_token' => $ya_result['access_token'], 'format' => 'json']))), true);

                if (isset($ya_user['id'])) {
                    if ($user_id = APP::Module('DB')->Select(
                        $this->conf['connection'], ['fetchColumn', 0], 
                        ['user_id'], 'social_accounts', 
                        [
                            ['network', '=', 'ya', PDO::PARAM_STR], 
                            ['extra', '=', $ya_user['id'], PDO::PARAM_STR]
                        ]
                    )) {
                        $this->user = $this->Auth($user_id, true, true);
                    } else {
                        if (isset($ya_user['default_email'])) {
                            if ($user_id = APP::Module('DB')->Select(
                                $this->conf['connection'], ['fetchColumn', 0], 
                                ['id'], 'users', [['email', '=', $ya_user['default_email'], PDO::PARAM_STR]]
                            )) {
                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"ya"',
                                        'extra' => [$ya_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $this->user = $this->Auth($user_id, true, true);
                            } else {
                                $password = $this->GeneratePassword((int) APP::Module('Registry')->Get('module_users_gen_pass_length'));
                                $user_id = $this->Register($ya_user['default_email'], $password, 'user');
                                $this->user = $this->Auth($user_id, true, true);

                                APP::Module('DB')->Insert(
                                    $this->conf['connection'], ' social_accounts',
                                    [
                                        'id' => 'NULL',
                                        'user_id' => [$user_id, PDO::PARAM_INT],
                                        'network' => '"ya"',
                                        'extra' => [$ya_user['id'], PDO::PARAM_STR],
                                        'up_date' => 'NOW()',
                                    ]
                                );

                                $letter = APP::Module('DB')->Select(
                                    APP::Module('Mail')->conf['connection'], 
                                    ['fetch', PDO::FETCH_ASSOC], 
                                    [
                                        'letters.subject', 
                                        'letters.html', 
                                        'letters.plaintext', 
                                        'letters.list_id',
                                        'senders.name',
                                        'senders.email'
                                    ], 
                                    'letters', 
                                    [['letters.id', '=', APP::Module('Registry')->Get('module_users_register_letter'), PDO::PARAM_INT]],
                                    ['join/senders' => [['senders.id','=','letters.sender_id']]]
                                );

                                $login_details = [
                                    'email' => $ya_user['default_email'],
                                    'password' => $password,
                                ];

                                APP::Module('Mail')->Send(
                                    [$letter['email'], $letter['name']], $ya_user['default_email'], $letter['subject'], 
                                    [
                                        APP::Render($letter['html'], 'eval', $login_details),
                                        APP::Render($letter['plaintext'], 'eval', $login_details)
                                    ],
                                    ['List-id' => $letter['list_id']]
                                );
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

}