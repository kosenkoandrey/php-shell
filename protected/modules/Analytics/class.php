<?
class Analytics {

    public $settings;

    function __construct($conf) {
        foreach ($conf['routes'] as $route)
            APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_analytics_db_connection',
            'module_analytics_yandex_token',
            'module_analytics_yandex_client_id',
            'module_analytics_yandex_client_secret',
            'module_analytics_yandex_auth_username',
            'module_analytics_yandex_auth_password',
            'module_analytics_yandex_site',
            'module_analytics_yandex_counter'
        ]);
    }
 
    public function Admin() {
        return APP::Render('analytics/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('analytics/admin/dashboard/index', 'return');
    }
    
    public function APIDashboard() {
        $range = [];

        for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
            $range[date('d-m-Y', $x)] = [
                'visit'      => 0,
                'page_views' => 0
            ];
        }      
        
        $yandex_list = [];
        foreach (APP::Module('DB')->Select(
                $this->settings['module_analytics_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                ['id', 'UNIX_TIMESTAMP(cr_date) as date', 'visit', 'page_views', 'UNIX_TIMESTAMP(up_date) as up_date'],
                'analytics_yandex_metrika',
                [
                    ['cr_date', 'BETWEEN', '"' . date('Y-m-d', $_POST['date']['from']) . ' 00:00:00" AND "' . date('Y-m-d', $_POST['date']['to']) . ' 00:00:00"', PDO::PARAM_STR]
                ], false,false, false, ['cr_date', 'desc']
        ) as $item) {
            $date_index = date('d-m-Y', $item['date']);

            if (!isset($range[$date_index])) {
                $range[$date_index] = [
                    'visit'      => 0,
                    'page_views' => 0
                ];
            }

            $range[$date_index]['visit'] = $range[$date_index]['visit'] + $item['visit'];
            $range[$date_index]['page_views'] = $range[$date_index]['page_views'] + $item['page_views'];
            
            $yandex_list[date('d.m.Y', $item['date'])] = [
                'id' => $item['id'],
                'cr_date' => date('d.m.Y', $item['date']),
                'visits'     => $item['visit'],
                'page_views' => $item['page_views'],
                'up_date'   => date('d.m.Y', $item['date'])
            ];
        }

        $out = [];

        foreach ($range as $date_index => $counters) {
            $out['visit'][$date_index]      = [strtotime($date_index) * 1000, $counters['visit']];
            $out['page_views'][$date_index] = [strtotime($date_index) * 1000, $counters['page_views']];
        }

        $out = [
            'stat' => APP::Module('DB')->Select($this->settings['module_analytics_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['*'], 'analytics_web_stats',
                false, false, false, false,
                ['cr_date', 'DESC'], [0, 1]
            ),
            'yandex'  => [
                'visit'       => array_values($out['visit']),
                'page_views'  => array_values($out['page_views'])
            ],
            'yandex_list' => $yandex_list
        ];

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function UpdateYandexStats() {
        $auth = $this->YandexAuth(
           $this->settings['module_analytics_yandex_auth_username'], $this->settings['module_analytics_yandex_auth_password'], $this->settings['module_analytics_yandex_client_id'], $this->settings['module_analytics_yandex_client_secret']
        );

        date_default_timezone_set('UTC');
        $date = date('Y-m-d', strtotime("-1 day"));

        $traffic = $this->Traffic(
            $this->settings['module_analytics_yandex_counter'], $this->settings['module_analytics_yandex_token'], array('start' => $date, 'end' => $date)
        );

        $this->SaveTraffic($traffic, $date);    
    }

    public function UpdateWebStats() {
        date_default_timezone_set('UTC');
        $date = date('Y-m-d');

        $cy = $this->CY($this->settings['module_analytics_yandex_site']);
        $this->SaveWebStats($cy, $date);
    }
    
    public function SaveTraffic(array $traffic, $date) {
        $exists = APP::Module('DB')->Select(
                $this->settings['module_analytics_db_connection'], ['fetch', PDO::FETCH_COLUMN], ['count(*)'], 'analytics_yandex_metrika', [
                ['cr_date', '=', $date, PDO::PARAM_STR]
                ]
        );

        if ($exists) {
            APP::Module('DB')->Update($this->settings['module_analytics_db_connection'], 'analytics_yandex_metrika', [
                'visits'     => $traffic['totals']['visits'],
                'page_views' => $traffic['totals']['page_views'],
                'up_date'    => date('Y-m-d H:i:s')
                    ], [
                    ['cr_date', '=', $date, PDO::PARAM_STR]
            ]);
            return;
        }

        APP::Module('DB')->Insert(
                $this->settings['module_analytics_db_connection'], 'analytics_yandex_metrika', [
            'id'         => 'NULL',
            'visit'      => [$traffic['totals']['visits'], PDO::PARAM_INT],
            'page_views' => [$traffic['totals']['page_views'], PDO::PARAM_INT],
            'cr_date'    => [$date, PDO::PARAM_STR],
            'up_date'    => [date('Y-m-d H:i:s'), PDO::PARAM_STR]
                ]
        );
    }

    public function SaveWebStats($cy = 0, $date) {
        $lastWebStat = APP::Module('DB')->Select(
            $this->settings['module_analytics_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['*'], 'analytics_web_stats', [
            ['cr_date', '<=', $date, PDO::PARAM_STR]
            ], false, false, false, ['cr_date', 'DESC'], [0, 1]
        );

        if ($lastWebStat) {
            if ($lastWebStat['cy'] != $cy) {
                APP::Module('DB')->Update($this->settings['module_analytics_db_connection'], 'analytics_web_stats', [
                    'cy'      => $cy,
                    'cr_date' => $date,
                    'up_date' => date('Y-m-d H:i:s')
                        ], [
                        ['id', '=', $lastWebStat['id'], PDO::PARAM_INT]
                ]);
            }
            return;
        }

        APP::Module('DB')->Insert(
                $this->settings['module_analytics_db_connection'], 'analytics_web_stats', [
            'id'      => 'NULL',
            'cy'      => [$cy, PDO::PARAM_INT],
            'pr'      => [0, PDO::PARAM_INT],
            'cr_date' => [$date, PDO::PARAM_STR],
            'up_date' => [date('Y-m-d H:i:s'), PDO::PARAM_STR]
                ]
        );
    }

    private function GetDownload($url) {
        $ret = false;

        if (function_exists('curl_init')) {
            if ($curl = curl_init()) {
                if (!curl_setopt($curl, CURLOPT_URL, $url))
                    return $ret;
                if (!curl_setopt($curl, CURLOPT_RETURNTRANSFER, true))
                    return $ret;
                if (!curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30))
                    return $ret;
                if (!curl_setopt($curl, CURLOPT_HEADER, false))
                    return $ret;
                if (!curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'))
                    return $ret;

                $ret = curl_exec($curl);

                curl_close($curl);
            }
        } else {
            $u = parse_url($url);

            if ($fp = @fsockopen($u['host'], !empty($u['port']) ? $u['port'] : 80)) {

                $headers = 'GET ' . $u['path'] . '?' . $u['query'] . ' HTTP/1.0' . "\r\n";
                $headers .= 'Host: ' . $u['host'] . "\r\n";
                $headers .= 'Connection: Close' . "\r\n\r\n";

                fwrite($fp, $headers);
                $ret = '';

                while (!feof($fp))
                    $ret .= fgets($fp, 1024);
                $ret = substr($ret, strpos($ret, "\r\n\r\n") + 4);

                fclose($fp);
            }
        }

        return $ret;
    }

    public function Traffic($counter, $oauth_token, $date_range, $group = 'day') {
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-metrika.yandex.ru/stat/traffic/summary.json?id=' . $counter . '&oauth_token=' . $oauth_token . '&date1=' . $date_range['start'] . '&date2=' . $date_range['end'] . '&group=' . $group);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
            'Accept: application/x-yametrika+json'
                )
        );
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, true);
    }

    public function CY($site) {
        $ret = 'N/A';

        if (substr($site, 0, 7) != 'http://')
            $site    = 'http://' . $site;
        if ($content = $this->GetDownload('http://bar-navig.yandex.ru/u?ver=2&url=' . urlencode($site) . '&show=1&post=0')) {
            preg_match("/value=\"(.\d*)\"/", $content, $tic);
            if (!empty($tic[1]))
                $ret = $tic[1];
        }

        return $ret;
    }

    public function YandexAuth($username, $password, $client_id, $client_secret) {

        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth.yandex.ru/authorize');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=password&username=' . $username . '&password=' . $password . '&client_id=' . $client_id . '&client_secret=' . $client_secret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
            'Content-type: application/json'
                )
        );
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data, true);
    }
    
    public function Settings() {
        APP::Render('analytics/admin/settings');
    }
    
    public function YandexSettings() {
        APP::Render('analytics/admin/yandex');
    }
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_db_connection']], [['item', '=', 'module_analytics_db_connection', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('update_analytics_settings', [
            'db_connection' => $_POST['module_analytics_db_connection']
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
    
    public function APIUpdateYandexSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_site']], [['item', '=', 'module_analytics_yandex_site', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_auth_password']], [['item', '=', 'module_analytics_yandex_auth_password', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_auth_username']], [['item', '=', 'module_analytics_yandex_auth_username', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_client_secret']], [['item', '=', 'module_analytics_yandex_client_secret', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_client_id']], [['item', '=', 'module_analytics_yandex_client_id', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_token']], [['item', '=', 'module_analytics_yandex_token', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_analytics_yandex_counter']], [['item', '=', 'module_analytics_yandex_counter', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('update_analytics_yandex_settings', [
            'module_analytics_yandex_auth_password' => $_POST['module_analytics_yandex_auth_password'],
            'module_analytics_yandex_auth_username' => $_POST['module_analytics_yandex_auth_username'],
            'module_analytics_yandex_client_secret' => $_POST['module_analytics_yandex_client_secret'],
            'module_analytics_yandex_client_id' => $_POST['module_analytics_yandex_client_id'],
            'module_analytics_yandex_token' => $_POST['module_analytics_yandex_token'],
            'module_analytics_yandex_counter' => $_POST['module_analytics_yandex_counter']
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
