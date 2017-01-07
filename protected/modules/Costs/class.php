<?
class Costs {

    public $settings;
    private $search;
    private $actions;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_costs_db_connection',
            'module_costs_tmp_dir',
            'module_costs_max_execution_time',
            'module_costs_yandex_token',
            'module_costs_yandex_client_id',
            'module_costs_yandex_client_secret',
            'module_costs_yandex_utm_source',
            'module_costs_yandex_utm_medium',
            'module_costs_facebook_token',
            'module_costs_facebook_client_id',
            'module_costs_facebook_client_secret'
        ]);
        
        $this->search = new CostsSearch();
        $this->actions = new CostsActions();
    }
    
    public function Admin() {
        return APP::Render('costs/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('costs/admin/dashboard/index', 'return');
    }

    
    public function Search($rules) {
        $out = Array();

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
    
    public function GetYandex() {
        if (empty($this->settings['module_costs_yandex_token'])) exit;
        set_time_limit($this->settings['module_costs_max_execution_time']);
        
        $out = [];
        $total_amt = 0;
        $date = isset(APP::Module('Routing')->get['date']) ? APP::Module('Routing')->get['date'] : date('Y-m-d', strtotime('-1 day'));
        
        $campaigns = json_decode(file_get_contents('https://api.direct.yandex.com/json/v5/campaigns', 0, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Accept-Language: ru',
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Bearer ' . $this->settings['module_costs_yandex_token']
                ],
                'content' => json_encode([
                    'method' => 'get',
                    'params' => [
                        'SelectionCriteria' => [
                            'States' => ['ON']
                        ],
                        'FieldNames' => [
                            'Id',
                            'Name'
                        ], 
                        'TextCampaignFieldNames' => [
                            'CounterIds',
                            'RelevantKeywords',
                            'Settings',
                            'BiddingStrategy'
                        ]
                    ]
                ])
            ]
        ])), true);
        
        if (!empty($campaigns)) {
            $campaigns_id = [];
            
            foreach ($campaigns['result']['Campaigns'] as $campaign) {
                $campaigns_id[] = $campaign['Id'];
                
                $banners_stat = json_decode(APP::Module('Utils')->Curl([
                    'url' => 'https://api.direct.yandex.ru/live/v4/json/',
                    'return_transfer' => 1,
                    'http_header' => [
                        'Content-Type' => 'application/json'
                    ],
                    'post' => json_encode([
                        'method' => 'GetBannersStat',
                        'param' => [
                            'CampaignID' => $campaign['Id'],
                            'StartDate' => $date,
                            'EndDate' => $date,
                            'GroupByColumns' => ['clPhrase'],
                            'Currency' => 'RUB',
                            'IncludeDiscount' => 'Yes',
                            'IncludeVAT' => 'Yes'
                        ],
                        'token' => $this->settings['module_costs_yandex_token']
                    ])
                ]), true);

                foreach ((array) $banners_stat['data']['Stat'] as $banner_stat) {
                    if ($banner_stat['Sum'] != 0) {
                        $total_amt = $total_amt + $banner_stat['Sum'];
                    
                        $out[] = [
                            'amount' => $banner_stat['Sum'],
                            'utm_source' => $this->settings['module_costs_yandex_utm_source'],
                            'utm_medium' => $this->settings['module_costs_yandex_utm_medium'],
                            'utm_campaign' => $campaign['Id'],
                            'utm_content' => $banner_stat['BannerID'],
                            'utm_term' => strripos($banner_stat['Phrase'], ' -') === false ? $banner_stat['Phrase'] : strstr($banner_stat['Phrase'],' -', true),
                            'utm_compaing_desc' => $campaign['Name']
                        ];
                    }
                }
                
                sleep(1);
            }
            
            $banners = [];
            
            foreach (array_chunk($campaigns_id, 10) as $value) {
                $ads_list = json_decode(APP::Module('Utils')->Curl([
                    'url' => 'https://api.direct.yandex.ru/live/v4/json/',
                    'return_transfer' => 1,
                    'http_header' => [
                        'Content-Type' => 'application/json'
                    ],
                    'post' => json_encode([
                        'method' => 'GetBanners',
                        'param' => [
                            'CampaignIDS' => $value,
                            'FieldNames' => ['CampaignID','Title','BannerID'],
                            'GetPhrases' => 'No'
                        ],
                        'token' => $this->settings['module_costs_yandex_token']
                    ])
                ]), true);

                if (isset($ads_list['data'])){
                    foreach ((array) $ads_list['data'] as $banner_data) {
                        $banners[$banner_data['BannerID']] = $banner_data['Title'];
                    }
                }
            }

            foreach ($out as $key => $value) {
                $out[$key]['utm_content_desc'] = isset($banners[$value['utm_content']]) ? $banners[$value['utm_content']] : '';
            }
        }

        foreach ($out as $key => $value) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_costs_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'costs',
                [
                    ['cost_date', '=', $date, PDO::PARAM_STR],
                    ['utm_source', '=', '"' . $value['utm_source'] . '"', PDO::PARAM_STR],
                    ['utm_medium', '=', '"' . $value['utm_medium'] . '"', PDO::PARAM_STR],
                    ['utm_campaign', '=', '"' . $value['utm_campaign'] . '"', PDO::PARAM_STR],
                    ['utm_term', '=', '"' . $value['utm_term'] . '"', PDO::PARAM_STR],
                    ['utm_content', '=', '"' . $value['utm_content'] . '"', PDO::PARAM_STR],
                    ['utm_compaing_desc', '=', '"' . $value['utm_compaing_desc'] . '"', PDO::PARAM_STR],
                    ['utm_content_desc', '=', '"' . $value['utm_content_desc'] . '"', PDO::PARAM_STR]
                ]
            )) {
                APP::Module('DB')->Insert(
                    $this->settings['module_costs_db_connection'], 'costs',
                    Array(
                        'id' => 'NULL',
                        'user_id' => '"0"',
                        'comment' => '"auto"',
                        'amount' => [$value['amount'], PDO::PARAM_STR],
                        'cost_date' => [$date, PDO::PARAM_STR],
                        'cr_date' => 'NOW()',
                        'utm_source' => [$value['utm_source'], PDO::PARAM_STR],
                        'utm_medium' => [$value['utm_medium'], PDO::PARAM_STR],
                        'utm_campaign' => [$value['utm_campaign'], PDO::PARAM_STR],
                        'utm_term' => [$value['utm_term'], PDO::PARAM_STR],
                        'utm_content' => [$value['utm_content'], PDO::PARAM_STR],
                        'utm_compaing_desc' => [$value['utm_compaing_desc'], PDO::PARAM_STR],
                        'utm_content_desc' => [$value['utm_content_desc'], PDO::PARAM_STR],
                    )
                );
            }
        }
        
        APP::Module('Triggers')->Exec(
            'download_yandex_costs', 
            [
                'out' => $out, 
                'date' => $date
            ]
        );
        
        if (isset(APP::Module('Routing')->get['debug'])) {
            echo 'TOTAL AMT: ' . $total_amt;
            print_r($out);
        }
    }
    
    public function GetYandexToken() {
        if (isset(APP::Module('Routing')->get['code'])) {
            $data = json_decode(APP::Module('Utils')->Curl([
                'url' => 'https://oauth.yandex.ru/token',
                'return_transfer' => 1,
                'post' => [
                    'grant_type' => 'authorization_code',
                    'code' => APP::Module('Routing')->get['code'],
                    'client_id' => $this->settings['module_costs_yandex_client_id'],
                    'client_secret' => $this->settings['module_costs_yandex_client_secret']
                ]
            ]));
            
            if ($data->access_token) {
                APP::Module('Registry')->Update(['value' => $data->access_token], [['item', '=', 'module_costs_yandex_token', PDO::PARAM_STR]]);
                header('Location: ' . APP::Module('Routing')->root . 'admin/costs/settings?yandex_token=success');
            } else {
                header('Location: ' . APP::Module('Routing')->root . 'admin/costs/settings?yandex_token=error');
            }
        } else {
            header('Location: https://oauth.yandex.ru/authorize?response_type=code&client_id=' . $this->settings['module_costs_yandex_client_id']);
        }
        
        exit;
    }

    
    public function ManageCosts() {
        APP::Render('costs/admin/index');
    }
    
    public function AddCost() {
        APP::Render('costs/admin/add');
    }
    
    public function EditCost() {
        $cost_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['cost_id_hash']);
        
        APP::Render(
            'costs/admin/edit', 'include', 
            [
                'cost' => APP::Module('DB')->Select(
                    $this->settings['module_costs_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['cost_date', 'amount', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'comment'], 'costs',
                    [['id', '=', $cost_id, PDO::PARAM_INT]]
                ),
            ]
        );
    }
    
    public function Settings() {
        APP::Render('costs/admin/settings');
    }
    
    
    public function APIDashboard() { 
        $tmp = [];
        
        $sources = APP::Module('DB')->Select(
            $this->settings['module_costs_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['DISTINCT(utm_source)'], 'costs'
        );
        
        for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
            foreach ($sources as $value) {
                $tmp[$value][date('d-m-Y', $x)] = [];
            }
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_costs_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'amount',
                'utm_source',
                'UNIX_TIMESTAMP(cost_date) AS cost_date'
            ], 
            'costs',
            [['UNIX_TIMESTAMP(cost_date)', 'BETWEEN', $_POST['date']['from'] . ' AND ' . $_POST['date']['to']]]
        ) as $cost) {
            $tmp[$cost['utm_source']][date('d-m-Y', $cost['cost_date'])][] = $cost['amount'];
        }
        
        $out = [];

        foreach ((array) $tmp as $source => $dates) {
            foreach ((array) $dates as $key => $value) {
                $out[$source][$key] = [strtotime($key) * 1000, array_sum((array) $value)];
            }
        }
        
        foreach ($out as $key => $value) {
            $out[$key] = array_values($value);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APISearchCosts() {
        $request = json_decode(file_get_contents('php://input'), true);
        $out = $this->Search(json_decode($request['search'], 1));
        $rows = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_costs_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'amount', 'cost_date AS date', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'], 'costs',
            [['id', 'IN', $out, PDO::PARAM_INT]], 
            false, false, false,
            [$request['sort_by'], $request['sort_direction']],
            [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['cost_id_token'] = APP::Module('Crypt')->Encode($row['id']);
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
    
    public function APIAddCost() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if ($out['status'] == 'success') {
            $out['cost_id'] = APP::Module('DB')->Insert(
                $this->settings['module_costs_db_connection'], 'costs',
                Array(
                    'id' => 'NULL',
                    'user_id' => '"0"',
                    'comment' => [$_POST['comment'], PDO::PARAM_STR],
                    'amount' => [$_POST['amount'], PDO::PARAM_STR],
                    'cost_date' => [$_POST['date'], PDO::PARAM_STR],
                    'cr_date' => 'NOW()',
                    'utm_source' => [$_POST['utm_source'], PDO::PARAM_STR],
                    'utm_medium' => [$_POST['utm_medium'], PDO::PARAM_STR],
                    'utm_campaign' => [$_POST['utm_campaign'], PDO::PARAM_STR],
                    'utm_term' => [$_POST['utm_term'], PDO::PARAM_STR],
                    'utm_content' => [$_POST['utm_content'], PDO::PARAM_STR],
                    'utm_compaing_desc' => '',
                    'utm_content_desc' => ''
                )
            );
            
            APP::Module('Triggers')->Exec('add_manual_cost', [
                'id' => $out['cost_id'],
                'user_id' => '"0"',
                'comment' => $_POST['comment'],
                'amount' => $_POST['amount'],
                'cost_date' => $_POST['date'],
                'utm_source' => $_POST['utm_source'],
                'utm_medium' => $_POST['utm_medium'],
                'utm_campaign' => $_POST['utm_campaign'],
                'utm_term' => $_POST['utm_term'],
                'utm_content' => $_POST['utm_content'],
                'utm_compaing_desc' => '',
                'utm_content_desc' => ''
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveCost() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_costs_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'costs',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_costs_db_connection'], 'costs',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('remove_cost', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateCost() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $cost_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select($this->settings['module_costs_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'costs', [['id', '=', $cost_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_costs_db_connection'], 'costs', [
                'cost_date' => $_POST['date'],
                'amount' => $_POST['amount'],
                'utm_source' => $_POST['utm_source'],
                'utm_medium' => $_POST['utm_medium'],
                'utm_campaign' => $_POST['utm_campaign'],
                'utm_term' => $_POST['utm_term'],
                'utm_content' => $_POST['utm_content'],
                'comment' => $_POST['comment']
            ], [
                ['id', '=', $cost_id, PDO::PARAM_INT]
            ]);

            APP::Module('Triggers')->Exec('update_cost', [
                'id' => $cost_id,
                'cost_date' => $_POST['date'],
                'amount' => $_POST['amount'],
                'utm_source' => $_POST['utm_source'],
                'utm_medium' => $_POST['utm_medium'],
                'utm_campaign' => $_POST['utm_campaign'],
                'utm_term' => $_POST['utm_term'],
                'utm_content' => $_POST['utm_content'],
                'comment' => $_POST['comment']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_db_connection']], [['item', '=', 'module_costs_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_tmp_dir']], [['item', '=', 'module_costs_tmp_dir', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_max_execution_time']], [['item', '=', 'module_costs_max_execution_time', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_yandex_utm_source']], [['item', '=', 'module_costs_yandex_utm_source', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_yandex_utm_medium']], [['item', '=', 'module_costs_yandex_utm_medium', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_yandex_client_id']], [['item', '=', 'module_costs_yandex_client_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_costs_yandex_client_secret']], [['item', '=', 'module_costs_yandex_client_secret', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_costs_settings', [
            'db_connection' => $_POST['module_costs_db_connection'],
            'tmp_dir' => $_POST['module_costs_tmp_dir'],
            'max_execution_time' => $_POST['module_costs_max_execution_time'],
            'yandex_utm_source' => $_POST['module_costs_yandex_utm_source'],
            'yandex_utm_medium' => $_POST['module_costs_yandex_utm_medium'],
            'yandex_client_id' => $_POST['module_costs_yandex_client_id'],
            'yandex_client_secret' => $_POST['module_costs_yandex_client_secret']
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
    
    
    public function BackwardCompatibilityYandexCosts($id, $data) {
        foreach ($data['out'] as $key => $value) {
            if (!APP::Module('DB')->Select(
                'pult_ref', ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'cost',
                [
                    ['cost_date', '=', $data['date'], PDO::PARAM_STR],
                    ['utm_source', '=', '"' . $value['utm_source'] . '"', PDO::PARAM_STR],
                    ['utm_medium', '=', '"' . $value['utm_medium'] . '"', PDO::PARAM_STR],
                    ['utm_campaign', '=', '"' . $value['utm_campaign'] . '"', PDO::PARAM_STR],
                    ['utm_term', '=', '"' . $value['utm_term'] . '"', PDO::PARAM_STR],
                    ['utm_content', '=', '"' . $value['utm_content'] . '"', PDO::PARAM_STR],
                    ['utm_compaing_desc', '=', '"' . $value['utm_compaing_desc'] . '"', PDO::PARAM_STR],
                    ['utm_content_desc', '=', '"' . $value['utm_content_desc'] . '"', PDO::PARAM_STR]
                ]
            )) {
                APP::Module('DB')->Insert(
                    'pult_ref', 'cost',
                    Array(
                        'id' => 'NULL',
                        'user_id' => '"0"',
                        'comment' => '"auto"',
                        'cost' => [$value['amount'], PDO::PARAM_STR],
                        'cost_date' => [$data['date'], PDO::PARAM_STR],
                        'cr_date' => 'NOW()',
                        'utm_source' => [$value['utm_source'], PDO::PARAM_STR],
                        'utm_medium' => [$value['utm_medium'], PDO::PARAM_STR],
                        'utm_campaign' => [$value['utm_campaign'], PDO::PARAM_STR],
                        'utm_term' => [$value['utm_term'], PDO::PARAM_STR],
                        'utm_content' => [$value['utm_content'], PDO::PARAM_STR],
                        'utm_compaing_desc' => [$value['utm_compaing_desc'], PDO::PARAM_STR],
                        'utm_content_desc' => [$value['utm_content_desc'], PDO::PARAM_STR],
                    )
                );
            }
        }
    }
    
}

class CostsSearch {

    public function date($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['cost_date', 'BETWEEN', '"' . $settings['from'] . '" AND "' . $settings['to'] . '"']]
        );
    }
    
    public function amount($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['amount', $settings['logic'], $settings['value'], PDO::PARAM_INT]]
        );
    }
    
    public function utm_source($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['utm_source', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function utm_medium($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['utm_medium', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function utm_campaign($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['utm_campaign', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function utm_term($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['utm_term', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }
    
    public function utm_content($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Costs')->settings['module_costs_db_connection'], 
            ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'costs',
            [['utm_content', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }

}

class CostsActions {

    public function remove($id, $settings) {
        return APP::Module('DB')->Delete(APP::Module('Costs')->settings['module_costs_db_connection'], 'costs', [['id', 'IN', $id]]);
    }
    
}