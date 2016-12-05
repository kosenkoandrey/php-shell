<?
class SocialNetworks {
    
    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_social_networks_ssh_connection',
            'module_social_networks_db_connection',
            'module_social_networks_tmp_dir',
            'module_social_networks_vk_gid',
            'module_social_networks_fb_name',
            'module_social_networks_gplus_user',
            'module_social_networks_gplus_key',
            'module_social_networks_twitter_user'
        ]);
    }
    
    public function Admin() {
        return APP::Render('social_networks/admin/nav', 'content');
    }
    

    public function UpdateFollowers() {
        $lock = fopen($this->settings['module_social_networks_tmp_dir'] . '/module_social_networks_get_followers.lock', 'w'); 
        
        if (flock($lock, LOCK_EX|LOCK_NB)) { 
            if ($this->settings['module_social_networks_vk_gid']) {
                $vk = json_decode(file_get_contents('http://api.vk.com/method/groups.getById?gid=' . $this->settings['module_social_networks_vk_gid'] . '&fields=members_count'), 1);

                if ($vk_id = APP::Module('DB')->Select(
                    $this->settings['module_social_networks_db_connection'], ['fetchColumn', 0], 
                    ['id'], 'social_networks_followers',
                    [
                        ['network', '=', 'vk', PDO::PARAM_STR],
                        ['date', '=', date('Y-m-d'), PDO::PARAM_STR]]
                )) {
                    APP::Module('DB')->Update($this->settings['module_social_networks_db_connection'], 'social_networks_followers', ['count' => $vk['response'][0]['members_count']], [['id', '=', $vk_id, PDO::PARAM_INT]]);
                } else {
                    APP::Module('DB')->Insert(
                        $this->settings['module_social_networks_db_connection'], 'social_networks_followers',
                        [
                            'id' => 'NULL',
                            'network' => ['vk', PDO::PARAM_STR],
                            'count' => [$vk['response'][0]['members_count'], PDO::PARAM_INT],
                            'cr_date' => 'NOW()'
                        ]
                    );
                }
            }
            
            if ($this->settings['module_social_networks_fb_name']) {
                $fb = file_get_contents('http://api.facebook.com/restserver.php?method=facebook.fql.query&query=' . rawurlencode('SELECT fan_count FROM page WHERE username="' . $this->settings['module_social_networks_fb_name'] . '"'));
                $fb_xml = simplexml_load_string($fb);

                if ($fb_id = APP::Module('DB')->Select(
                    $this->settings['module_social_networks_db_connection'], ['fetchColumn', 0], 
                    ['id'], 'social_networks_followers',
                    [
                        ['network', '=', 'fb', PDO::PARAM_STR],
                        ['date', '=', date('Y-m-d'), PDO::PARAM_STR]]
                )) {
                    APP::Module('DB')->Update($this->settings['module_social_networks_db_connection'], 'social_networks_followers', ['count' => $fb_xml->page->fan_count], [['id', '=', $fb_id, PDO::PARAM_INT]]);
                } else {
                    APP::Module('DB')->Insert(
                        $this->settings['module_social_networks_db_connection'], 'social_networks_followers',
                        [
                            'id' => 'NULL',
                            'network' => ['fb', PDO::PARAM_STR],
                            'count' => [$fb_xml->page->fan_count, PDO::PARAM_INT],
                            'cr_date' => 'NOW()'
                        ]
                    );
                }
            }
            
            if (($this->settings['module_social_networks_gplus_user']) && ($this->settings['module_social_networks_gplus_key'])) {
                $google = json_decode(file_get_contents('https://www.googleapis.com/plus/v1/people/' . $this->settings['module_social_networks_gplus_user'] .'?key=' . $this->settings['module_social_networks_gplus_key']), 1);           

                if ($gplus_id = APP::Module('DB')->Select(
                    $this->settings['module_social_networks_db_connection'], ['fetchColumn', 0], 
                    ['id'], 'social_networks_followers',
                    [
                        ['network', '=', 'gplus', PDO::PARAM_STR],
                        ['date', '=', date('Y-m-d'), PDO::PARAM_STR]
                    ]
                )) {
                    APP::Module('DB')->Update($this->settings['module_social_networks_db_connection'], 'social_networks_followers', ['count' => $google['circledByCount']], [['id', '=', $gplus_id, PDO::PARAM_INT]]);
                } else {
                    APP::Module('DB')->Insert(
                        $this->settings['module_social_networks_db_connection'], 'social_networks_followers',
                        [
                            'id' => 'NULL',
                            'network' => ['gplus', PDO::PARAM_STR],
                            'count' => [$google['circledByCount'], PDO::PARAM_INT],
                            'cr_date' => 'NOW()'
                        ]
                    );
                }
            }
            
            if ($this->settings['module_social_networks_twitter_user']) {
                $twitter = json_decode(file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=' . $this->settings['module_social_networks_twitter_user']), 1); 

                if ($twitter_id = APP::Module('DB')->Select(
                    $this->settings['module_social_networks_db_connection'], ['fetchColumn', 0], 
                    ['id'], 'social_networks_followers',
                    [
                        ['network', '=', 'twitter', PDO::PARAM_STR],
                        ['date', '=', date('Y-m-d'), PDO::PARAM_STR]
                    ]
                )) {
                    APP::Module('DB')->Update($this->settings['module_social_networks_db_connection'], 'social_networks_followers', ['count' => $twitter[0]['followers_count']], [['id', '=', $twitter_id, PDO::PARAM_INT]]);
                } else {
                    APP::Module('DB')->Insert(
                        $this->settings['module_social_networks_db_connection'], 'social_networks_followers',
                        [
                            'id' => 'NULL',
                            'network' => ['twitter', PDO::PARAM_STR],
                            'count' => [$twitter[0]['followers_count'], PDO::PARAM_INT],
                            'cr_date' => 'NOW()'
                        ]
                    );
                }
            }
            
            APP::Module('Triggers')->Exec('social_networks_update_followers', false);
        } else {
            exit;
        }
        
        fclose($lock);
    }
    
    public function GetFollowers($fields = ['count'], $last = true) {
        return [
            'vk' => $this->settings['module_social_networks_vk_gid'] ? APP::Module('DB')->Select(
                $this->settings['module_social_networks_db_connection'], $last ? ['fetchColumn', 0] : ['fetchAll', PDO::FETCH_ASSOC], 
                $fields, 'social_networks_followers',
                [['network', '=', 'vk', PDO::PARAM_STR]],
                false, false, false,
                ['id', 'desc']
            ) : false,
            'fb' => $this->settings['module_social_networks_fb_name'] ? APP::Module('DB')->Select(
                $this->settings['module_social_networks_db_connection'], $last ? ['fetchColumn', 0] : ['fetchAll', PDO::FETCH_ASSOC], 
                $fields, 'social_networks_followers',
                [['network', '=', 'fb', PDO::PARAM_STR]],
                false, false, false,
                ['id', 'desc']
            ) : false,
            'gplus' => $this->settings['module_social_networks_gplus_user'] && $this->settings['module_social_networks_gplus_key'] ? APP::Module('DB')->Select(
                $this->settings['module_social_networks_db_connection'], $last ? ['fetchColumn', 0] : ['fetchAll', PDO::FETCH_ASSOC], 
                $fields, 'social_networks_followers',
                [['network', '=', 'gplus', PDO::PARAM_STR]],
                false, false, false,
                ['id', 'desc']
            ) : false,
            'twitter' => $this->settings['module_social_networks_twitter_user'] ? APP::Module('DB')->Select(
                $this->settings['module_social_networks_db_connection'], $last ? ['fetchColumn', 0] : ['fetchAll', PDO::FETCH_ASSOC], 
                $fields, 'social_networks_followers',
                [['network', '=', 'twitter', PDO::PARAM_STR]],
                false, false, false,
                ['id', 'desc']
            ) : false
        ];
    }

    
    public function Overview() {
        APP::Render('social_networks/admin/index', 'include', $this->GetFollowers(['date', 'count'], false));
    }
    
    public function Settings() {
        APP::Render('social_networks/admin/settings');
    }
    
    public function Other() {
        APP::Render('social_networks/admin/other');
    }
    
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_vk_gid']], [['item', '=', 'module_social_networks_vk_gid', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_fb_name']], [['item', '=', 'module_social_networks_fb_name', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_gplus_user']], [['item', '=', 'module_social_networks_gplus_user', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_gplus_key']], [['item', '=', 'module_social_networks_gplus_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_twitter_user']], [['item', '=', 'module_social_networks_twitter_user', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('social_networks_update_settings', [
            'vk_gid' => $_POST['module_social_networks_vk_gid'],
            'fb_name' => $_POST['module_social_networks_fb_name'],
            'gplus_user' => $_POST['module_social_networks_gplus_user'],
            'gplus_key' => $_POST['module_social_networks_gplus_key'],
            'twitter_user' => $_POST['module_social_networks_twitter_user']
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
    
    public function APIUpdateOther() {
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_db_connection']], [['item', '=', 'module_social_networks_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_social_networks_tmp_dir']], [['item', '=', 'module_social_networks_tmp_dir', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('social_networks_update_other', [
            'db_connection' => $_POST['module_social_networks_db_connection'],
            'tmp_dir' => $_POST['module_social_networks_tmp_dir']
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