<?
class Mail {
    
    public $settings;
    private $fbl_folders = ['yandex', 'mail'];
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_mail_db_connection',
            'module_mail_ssh_connection',
            'module_mail_tmp_dir',
            'module_mail_charset',
            'module_mail_x_mailer',
            'module_mail_save_sent_email',
            'module_mail_sent_email_lifetime',
            'module_mail_fbl_server',
            'module_mail_fbl_login',
            'module_mail_fbl_password',
            'module_mail_fbl_prefix'
        ]);
    }
    
    public function Admin() {
        return APP::Render('mail/admin/nav', 'content');
    }
    
    public function Dashboard() {
        return APP::Render('mail/admin/dashboard/index', 'return');
    }
    
    
    public function PrepareSend($recepient, $letter, $params = []) {
        $params['recepient'] = $recepient;
        
        $letter = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['id', 'group_id', 'sender', 'subject', 'html', 'plaintext', 'transport', 'priority'], 'mail_letters',
            [['id', '=', $letter, PDO::PARAM_INT]]
        );
        
        $letter['subject'] = APP::Render($letter['subject'], 'eval', $params);
        $letter['html'] = APP::Render($letter['html'], 'eval', $params);
        $letter['plaintext'] = APP::Render($letter['plaintext'], 'eval', $params);
        
        // shortcodes
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['code', 'content'], 'mail_shortcodes'
        ) as $shortcode) {
            $letter['html'] = str_replace('[' . $shortcode['code'] . ']', $shortcode['content'], $letter['html']);
            $letter['plaintext'] = str_replace('[' . $shortcode['code'] . ']', $shortcode['content'], $letter['plaintext']);
        }
        //
        
        // [token]
        $token = APP::Module('Crypt')->Encode(json_encode([
            'email' => $recepient,
            'letter' => $letter['id'],
            'params' => $params
        ]));
        
        $letter['html'] = str_replace('[token]', $token, $letter['html']);
        $letter['plaintext'] = str_replace('[token]', $token, $letter['plaintext']);
        //
        
        // [user_email]
        $letter['subject'] = str_replace('[user_email]', $recepient, $letter['subject']);
        $letter['html'] = str_replace('[user_email]', $recepient, $letter['html']);
        $letter['plaintext'] = str_replace('[user_email]', $recepient, $letter['plaintext']);
        //
        
        // [encode][/encode]
        preg_match_all('/\[encode\](.*)\[\/encode\]/i', $letter['subject'], $subject_matches);
        preg_match_all('/\[encode\](.*)\[\/encode\]/i', $letter['html'], $html_matches);
        preg_match_all('/\[encode\](.*)\[\/encode\]/i', $letter['plaintext'], $plaintext_matches);

        foreach ($subject_matches[0] as $key => $pattern) $letter['subject'] = str_replace($pattern, APP::Module('Crypt')->Encode($subject_matches[1][$key]), $letter['subject']);
        foreach ($html_matches[0] as $key => $pattern) $letter['html'] = str_replace($pattern, APP::Module('Crypt')->Encode($html_matches[1][$key]), $letter['html']);
        foreach ($plaintext_matches[0] as $key => $pattern) $letter['plaintext'] = str_replace($pattern, APP::Module('Crypt')->Encode($plaintext_matches[1][$key]), $letter['plaintext']);
        //
        
        // [decode][/decode]
        preg_match_all('/\[decode\](.*)\[\/decode\]/i', $letter['subject'], $subject_matches);
        preg_match_all('/\[decode\](.*)\[\/decode\]/i', $letter['html'], $html_matches);
        preg_match_all('/\[decode\](.*)\[\/decode\]/i', $letter['plaintext'], $plaintext_matches);

        foreach ($subject_matches[0] as $key => $pattern) $letter['subject'] = str_replace($pattern, APP::Module('Crypt')->Decode($subject_matches[1][$key]), $letter['subject']);
        foreach ($html_matches[0] as $key => $pattern) $letter['html'] = str_replace($pattern, APP::Module('Crypt')->Decode($html_matches[1][$key]), $letter['html']);
        foreach ($plaintext_matches[0] as $key => $pattern) $letter['plaintext'] = str_replace($pattern, APP::Module('Crypt')->Decode($plaintext_matches[1][$key]), $letter['plaintext']);
        //
        
        // [spamreport-link]
        $spamreport_link = APP::Module('Routing')->root . 'mail/spamreport/[letter_hash]';
        
        $letter['html'] = str_replace('[spamreport-link]', $spamreport_link, $letter['html']);
        $letter['plaintext'] = str_replace('[spamreport-link]', $spamreport_link, $letter['plaintext']);
        //

        extract(APP::Module('Triggers')->Exec('before_mail_send_letter', [
            'recepient' => $recepient,
            'letter' => $letter,
            'params' => $params
        ]));

        return $letter;
    }
    
    public function Send($recepient, $letter, $params = []) {
        if (!filter_var($recepient, FILTER_VALIDATE_EMAIL)) return ['error', 1];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_letters',
            [['id', '=', $letter, PDO::PARAM_INT]]
        )) {
            return ['error', 2];
        }
        
        $letter = $this->PrepareSend($recepient, $letter, $params);
        
        $transport = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['module', 'method'], 'mail_transport',
            [['id', '=', $letter['transport'], PDO::PARAM_INT]]
        );
        
        $result = APP::Module($transport['module'])->{$transport['method']}($recepient, $letter, $params);

        APP::Module('Triggers')->Exec('after_mail_send_letter', [
            'result' => $result,
            'recepient' => $recepient,
            'letter' => $letter,
            'params' => $params
        ]);
        
        return $result;
    }

    private function Transport($recepient, $letter, $params) {
        $id = false;
        
        if (isset(APP::$modules['Users'])) {
            $user = (int) APP::Module('DB')->Select(
                APP::Module('Users')->settings['module_users_db_connection'], ['fetchColumn', 0], 
                ['id'], 'users', [['email', '=', $recepient, PDO::PARAM_STR]]
            );
            
            if ($user) {
                $letter['subject'] = str_replace('[user_id]', $user, $letter['subject']);
                $letter['html'] = str_replace('[user_id]', $user, $letter['html']);
                $letter['plaintext'] = str_replace('[user_id]', $user, $letter['plaintext']);
                
                $id = APP::Module('DB')->Insert(
                    $this->settings['module_mail_db_connection'], 'mail_log',
                    [
                        'id' => 'NULL',
                        'user' => [$user, PDO::PARAM_INT],
                        'letter' => [$letter['id'], PDO::PARAM_INT],
                        'sender' => [$letter['sender'], PDO::PARAM_INT],
                        'transport' => [$letter['transport'], PDO::PARAM_INT],
                        'state' => ['wait', PDO::PARAM_STR],
                        'result' => 'NULL',
                        'retries' => 0,
                        'ping' => 0,
                        'cr_date' => 'NOW()'
                    ]
                );
                
                if ($id) {
                    $letter_hash = APP::Module('Crypt')->Encode($id);
                    
                    $letter['subject'] = str_replace('[letter_hash]', $letter_hash, $letter['subject']);
                    $letter['html'] = str_replace('[letter_hash]', $letter_hash, $letter['html']);
                    $letter['plaintext'] = str_replace('[letter_hash]', $letter_hash, $letter['plaintext']);

                    if ($this->settings['module_mail_save_sent_email']) {
                        APP::Module('DB')->Insert(
                            $this->settings['module_mail_db_connection'], 'mail_copies',
                            [
                                'id' => 'NULL',
                                'log' => [$id, PDO::PARAM_INT],
                                'subject' => [$letter['subject'], PDO::PARAM_STR],
                                'html' => [$letter['html'], PDO::PARAM_STR],
                                'plaintext' => [$letter['plaintext'], PDO::PARAM_STR],
                                'cr_date' => 'NOW()'
                            ]
                        );
                    }
                }
            }
        }

        $sender = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['name', 'email'], 'mail_senders',
            [['id', '=', $letter['sender'], PDO::PARAM_INT]]
        );

        $boundary = md5(uniqid());

        $headers = [
            'From: ' . mb_encode_mimeheader($sender['name'], $this->settings['module_mail_charset'], "B") . ' <' . $sender['email'] . '>',
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'Message-ID: <' . sprintf("%s.%s@%s", base_convert(microtime(), 10, 36), base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36), APP::$conf['location'][1]) . '>',
            'X-Mailer: ' . $this->settings['module_mail_x_mailer']
        ];
        
        if (isset($params['headers'])) {
            foreach ($params['headers'] as $key => $value) {
                if ($value) $headers[] = $key . ': ' . $value;
            }
        }

        $message = "--" . $boundary . "\r\n"
            . "Content-transfer-encoding: base64\r\n"
            . "Content-Type: text/plain; charset=" . $this->settings['module_mail_charset'] . "\r\n"
            . "Mime-Version: 1.0\r\n"
            . "\r\n"
            . chunk_split(base64_encode($letter['plaintext']))
            . "\r\n--" . $boundary . "\r\n"
            . "Content-transfer-encoding: base64\r\n"
            . "Content-Type: text/html; charset=" . $this->settings['module_mail_charset'] . "\r\n"
            . "Mime-Version: 1.0\r\n"
            . "\r\n"
            . chunk_split(base64_encode($letter['html']))
            . "\r\n--" . $boundary . "--\r\n";

        $result = mail(
            $recepient, 
            mb_encode_mimeheader($letter['subject'], $this->settings['module_mail_charset'], "B"), 
            $message, 
            implode("\r\n", $headers), 
            '-fbounce-' . md5($recepient) . '@' . APP::$conf['location'][1]
        );
        
        if ($id) {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'mail_log', 
                [
                    'state' => $result ? 'success' : 'error',
                    'retries' => 1
                ], 
                [['id', '=', $id, PDO::PARAM_INT]]
            );
        }

        return [
            'result' => $result, 
            'id' => $id
        ];
    }

    private function RenderLettersGroupsPath($group) {
        return $this->GetLettersGroupsPath(0, $this->RenderLettersGroups(), $group);
    }
    
    private function RenderSendersGroupsPath($group) {
        return $this->GetSendersGroupsPath(0, $this->RenderSendersGroups(), $group);
    }
    
    private function GetLettersGroupsPath($group, $data, $target, $path = Array()) {
        $path[$group] = $data['name'];

        if ($group == $target) {
            return $path;
        }

        if (count($data['groups'])) {
            foreach ($data['groups'] as $key => $value) {
                $res = $this->GetLettersGroupsPath($key, $value, $target, $path);
                
                if ($res) {
                    return $res;
                }
            }
        }

        unset($path[$group]);
        return false;
    }
    
    private function GetSendersGroupsPath($group, $data, $target, $path = Array()) {
        $path[$group] = $data['name'];

        if ($group == $target) {
            return $path;
        }

        if (count($data['groups'])) {
            foreach ($data['groups'] as $key => $value) {
                $res = $this->GetSendersGroupsPath($key, $value, $target, $path);
                
                if ($res) {
                    return $res;
                }
            }
        }

        unset($path[$group]);
        return false;
    }
    
    private function RenderLettersGroups() {
        $out = [
            0 => [
                'name' => '/',
                'groups' => []
            ]
        ];
        
        $letter_groups = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'sub_id', 'name'], 'mail_letters_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        );

        foreach ($letter_groups as $group) {
            $out[$group['id']] = [
                'name' => $group['name'],
                'groups' => []
            ];
        }
        
        foreach ($letter_groups as $group) {
            $out[$group['sub_id']]['groups'][$group['id']] = $group['name'];
        }

        return $this->GetLettersGroups($out, $out[0]);
    }
    
    private function RenderSendersGroups() {
        $out = [
            0 => [
                'name' => '/',
                'groups' => []
            ]
        ];
        
        $sender_groups = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'sub_id', 'name'], 'mail_senders_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        );

        foreach ($sender_groups as $group) {
            $out[$group['id']] = [
                'name' => $group['name'],
                'groups' => []
            ];
        }
        
        foreach ($sender_groups as $group) {
            $out[$group['sub_id']]['groups'][$group['id']] = $group['name'];
        }

        return $this->GetSendersGroups($out, $out[0]);
    }
    
    private function GetLettersGroups($groups, $data) {
        if (count($data['groups'])) {
            foreach ($data['groups'] as $id => $name) {
                $data['groups'][$id] = $this->GetLettersGroups($groups, $groups[$id]);
            }
        }
        
        return $data;
    }
    
    private function GetSendersGroups($groups, $data) {
        if (count($data['groups'])) {
            foreach ($data['groups'] as $id => $name) {
                $data['groups'][$id] = $this->GetSendersGroups($groups, $groups[$id]);
            }
        }
        
        return $data;
    }
    
    private function RemoveLettersGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'mail_letters_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveLettersGroup($value);
        }
        
        APP::Module('DB')->Delete(
            $this->settings['module_mail_db_connection'], 'mail_letters_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }
    
    private function RemoveSendersGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'mail_senders_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveSendersGroup($value);
        }
        
        APP::Module('DB')->Delete(
            $this->settings['module_mail_db_connection'], 'mail_senders_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }
    
    
    public function CopiesGC() {
        $lock = fopen($this->settings['module_mail_tmp_dir'] . '/module_mail_copies_gc.lock', 'w'); 
        
        if (flock($lock, LOCK_EX|LOCK_NB)) { 
            APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'mail_copies',
                [
                    ['UNIX_TIMESTAMP(cr_date)', '<=', strtotime('-' . $this->settings['module_mail_sent_email_lifetime']) , PDO::PARAM_INT]
                ]
            );
        } else { 
            exit;
        }
        
        fclose($lock);
    }
    
    public function IPSpamLists() {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'addr'], 'mail_ip'
        ) as $ip) {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, 'http://addgadgets.com/ip_blacklist/index.php?ipaddr=' . $ip['addr']);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Encoding: gzip,deflate,sdch',
                'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Connection: keep-alive',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie:__utma=119591256.1050640403.1408624841.1408624841.1408624841.1; __utmb=119591256.2.10.1408624841; __utmc=119591256; __utmz=119591256.1408624841.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); ddfmcode=60bc31a348b04897e975bf5024255d6c',
                'Host: addgadgets.com',
                'Referer: http://addgadgets.com/ip_blacklist/index.php?ipaddr=' . $ip['addr']
            ));

            $raw_data = curl_exec($ch);
            curl_close($ch);

            preg_match_all("/\<img\ src\=\"http\:\/\/addgadgets\.com\/image\/right\.png\"\ alt\=\"Not\ Listed\" \/\> (([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6})\<br\ \/\>/", $raw_data, $right);
            preg_match_all("/\<img\ src\=\"http\:\/\/addgadgets\.com\/image\/wrong\.png\"\ alt\=\"Listed\" \/\> \<span\ style\=\"color\:\#ff0000\"\>(([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6})\<\/span\>\<br\ \/\>/", $raw_data, $wrong);

            $out = [];

            foreach ($right[1] as $service) $out[$service] = 'right';
            foreach ($wrong[1] as $service) $out[$service] = 'wrong';

            $ignore = [
                'dyn.shlink.org',
                'bl.shlink.org',
                'tor.ahbl.org',
                'dnsbl.ahbl.org'
            ];
            
            APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'mail_ip_status',
                [['ip', '=', $ip['id'], PDO::PARAM_INT]]
            );

            foreach ($out as $service => $state) {
                if (!in_array($service, $ignore)){
                    APP::Module('DB')->Insert(
                        $this->settings['module_mail_db_connection'], 'mail_ip_status',
                        [
                            'id' => 'NULL',
                            'ip' => [$ip['id'], PDO::PARAM_INT],
                            'service' => [$service, PDO::PARAM_STR],
                            'state' => [$state, PDO::PARAM_STR],
                            'cr_date' => 'NOW()'
                        ]
                    );
                }
            }
        }
    }
    
    public function FBLReports() {
        foreach ($this->fbl_folders as $folder) {
            $out = Array();
        
            $mbox = imap_open('{' . $this->settings['module_mail_fbl_server'] . '/imap/ssl}' . $this->settings['module_mail_fbl_prefix'] . $folder, $this->settings['module_mail_fbl_login'], $this->settings['module_mail_fbl_password']);
            $mc = imap_check($mbox);
            $result = imap_fetch_overview($mbox, '1:' . $mc->Nmsgs, 0);

            foreach ($result as $email) {
                $body = imap_qprint(imap_body($mbox, $email->msgno)); 
                
                switch ($folder) {
                    case 'yandex':
                        preg_match("/bounce\-(.*)\@/", $body, $mtch);
                        
                        if (isset($mtch[1])) {
                            if (array_key_exists($mtch[1], $out) === false) {
                                $out[$mtch[1]] = APP::Module('DB')->Select(
                                    APP::Module('Users')->settings['module_users_db_connection'], ['fetchColumn', 0], 
                                    ['id'], 'users', [['MD5(email)', '=', $mtch[1], PDO::PARAM_STR]]
                                );
                            }
                        }
                        break;
                    case 'mail':
                        preg_match("/To\: ([a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)\r\n/", $body, $mtch);
                        
                        if (isset($mtch[1])) {
                            if (array_key_exists($mtch[1], $out) === false) {
                                $out[$mtch[1]] = APP::Module('DB')->Select(
                                    APP::Module('Users')->settings['module_users_db_connection'], ['fetchColumn', 0], 
                                    ['id'], 'users', [['email', '=', $mtch[1], PDO::PARAM_STR]]
                                );
                            }
                        }
                        break;
                }

                imap_mail_move($mbox, $email->msgno, $this->settings['module_mail_fbl_prefix'] . 'archive');
            }

            imap_expunge($mbox);
            imap_close($mbox);

            foreach ($out as $user_id) {
                APP::Module('DB')->Insert(
                    APP::Module('Users')->settings['module_users_db_connection'], 'users_tags',
                    [
                        'id' => 'NULL',
                        'user' => [$user_id, PDO::PARAM_INT],
                        'item' => ['fbl_report', PDO::PARAM_STR],
                        'value' => [$folder, PDO::PARAM_STR],
                        'cr_date' => 'NOW()'
                    ]
                );
                
                APP::Module('DB')->Update(
                    APP::Module('Users')->settings['module_users_db_connection'], 'users_about', 
                    ['value' => 'blacklist'], 
                    [
                        ['user', '=', $user_id, PDO::PARAM_INT],
                        ['item', '=', 'state', PDO::PARAM_STR]
                    ]
                );
            }
            
            APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_fbl_log',
                [
                    'id' => 'NULL',
                    'service' => [$folder, PDO::PARAM_STR],
                    'users' => [count($out), PDO::PARAM_INT],
                    'cr_date' => 'NOW()'
                ]
            );
        }
    }
    
    public function OpenPCT() {
        ini_set('max_execution_time','3600'); 
        ini_set('memory_limit','8192M');
        
        $count_letters = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['user', 'COUNT(DISTINCT id) AS count'], 'mail_log',
            [['state', '=', 'success', PDO::PARAM_STR]],
            false,
            ['user']
        ) as $value) {
            $count_letters[$value['user']] = $value['count'];
        }
        
        $count_events = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['user', 'COUNT(DISTINCT log) AS count'], 'mail_events',
            [['event', '=', 'open', PDO::PARAM_STR]],
            false,
            ['user']
        ) as $value) {
            $count_events[$value['user']] = $value['count'];
        }
        
        APP::Module('DB')->Open($this->settings['module_mail_db_connection'])->query('LOCK TABLES mail_open_pct');
        APP::Module('DB')->Open($this->settings['module_mail_db_connection'])->query('TRUNCATE TABLE mail_open_pct');
        
        foreach (APP::Module('DB')->Select(APP::Module('Users')->settings['module_users_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], ['id'], 'users') as $user) {
            $pct = ((isset($count_events[$user])) && (isset($count_letters[$user]))) ? ceil((int) $count_events[$user] / ((int) $count_letters[$user] / 100)) : 0;
            
            APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_open_pct',
                Array(
                    'user' => [$user, PDO::PARAM_INT],
                    'pct' => [$pct, PDO::PARAM_INT]
                )
            );
        }
    }
    
    public function OpenPCT30() {
        
    }
    
    public function ClickPCT() {
        
    }
    
    public function ClickPCT30() {
        
    }
    

    public function ManageLetters() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        $list = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name'], 'mail_letters_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'subject'], 'mail_letters',
            [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['letter', $value['id'], $value['subject']];
        }

        APP::Render('mail/admin/letters/index', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderLettersGroupsPath($group_sub_id),
            'list' => $list
        ]);
    }
    
    public function ManageSenders() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        $list = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name'], 'mail_senders_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name', 'email'], 'mail_senders',
            [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['sender', $value['id'], $value['name'], $value['email']];
        }

        APP::Render('mail/admin/senders/index', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderSendersGroupsPath($group_sub_id),
            'list' => $list
        ]);
    }
    
    public function PreviewLetter() {
        $user_id = isset(APP::Module('Routing')->get['user_id']) ? (int) APP::Module('Routing')->get['user_id'] : 0;
        $user_tunnel_id = 0;
        $tunnel_id = 0;
        $tunnels = [];
        
        if ((isset(APP::Module('Routing')->get['user_tunnel_id'])) && ($user_id)) {
            $user_tunnel_id = APP::Module('Routing')->get['user_tunnel_id'];
            $tunnel_id = APP::Module('DB')->Select(
                APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['tunnel_id'], 'tunnels_users',
                [['id', '=', $user_tunnel_id, PDO::PARAM_INT]]
            );
        }
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'type', 'name'], 'tunnels'
        ) as $tunnel) {
            $tunnels[$tunnel['id']] = $tunnel;
        }
        
        APP::Render(
            'mail/admin/letters/preview', 'include', 
            [
                'letter' => $this->PrepareSend(
                    $user_id, 
                    APP::Module('Crypt')->Decode(APP::Module('Routing')->get['letter_id_hash']), 
                    [
                        'user_tunnel_id' => $user_tunnel_id,
                        'tunnel_id' => $tunnel_id,
                    ]
                ),
                'user_id' => $user_id,
                'user_tunnel_id' => $user_tunnel_id,
                'tunnels' => $tunnels,
                'user_tunnels' => APP::Module('DB')->Select(
                    APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    ['id', 'tunnel_id', 'state'], 'tunnels_users',
                    [['user_id', '=', $user_id, PDO::PARAM_INT]]
                )
            ]
        );
    }
    
    public function AddLetter() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        APP::Render('mail/admin/letters/add', 'include', [
            'senders' => APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'name', 'email'], 'mail_senders'
            ),
            'transport' => APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['id', 'module', 'method'], 'mail_transport'
            ),
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderLettersGroupsPath($group_sub_id)
        ]);
    }
    
    public function AddSender() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        APP::Render('mail/admin/senders/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderSendersGroupsPath($group_sub_id)
        ]);
    }
    
    public function EditLetter() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $letter_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['letter_id_hash']);
        
        APP::Render(
            'mail/admin/letters/edit', 'include', 
            [
                'senders' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    ['id', 'name', 'email'], 'mail_senders'
                ),
                'letter' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['sender', 'subject', 'html', 'plaintext', 'transport', 'priority'], 'mail_letters',
                    [['id', '=', $letter_id, PDO::PARAM_INT]]
                ),
                'transport' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    ['id', 'module', 'method'], 'mail_transport'
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderLettersGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function EditSender() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $sender_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['sender_id_hash']);
        
        APP::Render(
            'mail/admin/senders/edit', 'include', 
            [
                'sender' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['name', 'email'], 'mail_senders',
                    [['id', '=', $sender_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderSendersGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function AddLettersGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('mail/admin/letters/groups/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderLettersGroupsPath($group_sub_id)
        ]);
    }
    
    public function AddSendersGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('mail/admin/senders/groups/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderSendersGroupsPath($group_sub_id)
        ]);
    }
    
    public function EditLettersGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $group_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);
        
        APP::Render(
            'mail/admin/letters/groups/edit', 'include', 
            [
                'group' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['name'], 'mail_letters_groups',
                    [['id', '=', $group_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderLettersGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function EditSendersGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $group_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);
        
        APP::Render(
            'mail/admin/senders/groups/edit', 'include', 
            [
                'group' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['name'], 'mail_senders_groups',
                    [['id', '=', $group_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderSendersGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function Settings() {
        APP::Render('mail/admin/settings');
    }
    
    public function ManageTransports() {
        APP::Render('mail/admin/transport/index');
    }
    
    public function AddTransport() {
        APP::Render('mail/admin/transport/add');
    }
    
    public function EditTransport() {
        $transport_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['transport_id_hash']);
        
        APP::Render('mail/admin/transport/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['module', 'method', 'settings'], 'mail_transport',
            [['id', '=', $transport_id, PDO::PARAM_INT]]
        ));
    }
    
    
    
    public function ManageShortcodes() {
        APP::Render('mail/admin/shortcodes/index');
    }
    
    public function AddShortcode() {
        APP::Render('mail/admin/shortcodes/add');
    }
    
    public function EditShortcode() {
        $shortcode_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['shortcode_id_hash']);
        
        APP::Render('mail/admin/shortcodes/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['code', 'content'], 'mail_shortcodes',
            [['id', '=', $shortcode_id, PDO::PARAM_INT]]
        ));
    }
    
    public function PreviewShortcode() {
        APP::Render(
            'mail/admin/shortcodes/preview', 'include', 
            APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['code', 'content'], 'mail_shortcodes',
                [['id', '=', (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['shortcode_id_hash']), PDO::PARAM_INT]]
            )
        );
    }
    
    
    
    public function ManageLog() {
        APP::Render('mail/admin/log');
    }
    
    public function ManageQueue() {
        APP::Render('mail/admin/queue');
    }
    
    public function ViewCopies() {
        if (!isset(APP::Module('Routing')->get['letter_id_hash'])) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        
        $letter_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['letter_id_hash']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_copies',
            [['log', '=', $letter_id, PDO::PARAM_INT]]
        )) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        
        APP::Render('mail/admin/copy', 'include', APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            [APP::Module('Routing')->get['version']], 'mail_copies',
            [['log', '=', $letter_id, PDO::PARAM_INT]]
        ));
    }

    public function ManageIPSpamLists() {
        APP::Render('mail/admin/spam_lists/index');
    }
    
    public function AddIPSpamLists() {
        APP::Render('mail/admin/spam_lists/add_ip');
    }
    
    public function IPStatusSpamLists() {
        APP::Render('mail/admin/spam_lists/status_ip', 'include', [
            'ip' => APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'addr'], 'mail_ip',
                [['id', '=', APP::Module('Crypt')->Decode(APP::Module('Routing')->get['ip_id_hash']), PDO::PARAM_INT]]
            )
        ]);
    }
    
    public function ManageFBLReports() {
        APP::Render('mail/admin/fbl');
    }

    
    public function APIAddLetter() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = $_POST['group_id'] ? APP::Module('Crypt')->Decode($_POST['group_id']) : 0;

        if ($group_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'mail_letters_groups',
                [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders',
            [['id', '=', $_POST['sender'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['subject'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['html'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['plaintext'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_transport',
            [['id', '=', $_POST['transport'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if (empty($_POST['priority'])) {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }
        
        if ($out['status'] == 'success') {
            $out['letter_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_letters',
                Array(
                    'id' => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'sender' => [$_POST['sender'], PDO::PARAM_INT],
                    'subject' => [$_POST['subject'], PDO::PARAM_STR],
                    'html' => [$_POST['html'], PDO::PARAM_STR],
                    'plaintext' => [$_POST['plaintext'], PDO::PARAM_STR],
                    'transport' => [$_POST['transport'], PDO::PARAM_INT],
                    'priority' => [$_POST['priority'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('mail_add_letter', [
                'id' => $out['letter_id'],
                'group_id' => $group_id,
                'sender' => $_POST['sender'],
                'subject' => $_POST['subject'],
                'html' => $_POST['html'],
                'plaintext' => $_POST['plaintext'],
                'transport' => $_POST['transport'],
                'priority' => $_POST['priority']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIAddSender() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = $_POST['group_id'] ? APP::Module('Crypt')->Decode($_POST['group_id']) : 0;

        if ($group_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'mail_senders_groups',
                [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }
        
        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['email'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if ($out['status'] == 'success') {
            $out['sender_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_senders',
                Array(
                    'id' => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'email' => [$_POST['email'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('mail_add_sender', [
                'id' => $out['sender_id'],
                'group_id' => $group_id,
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveLetter() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_letters',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'mail_letters',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_remove_letter', [
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
    
    public function APIRemoveSender() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'mail_senders',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_remove_sender', [
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
    
    public function APIUpdateLetter() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $letter_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_letters',
            [['id', '=', $letter_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders',
            [['id', '=', $_POST['sender'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['subject'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['html'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['plaintext'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_transport',
            [['id', '=', $_POST['transport'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if (empty($_POST['priority'])) {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'mail_letters', 
                [
                    'sender' => $_POST['sender'],
                    'subject' => $_POST['subject'],
                    'html' => $_POST['html'],
                    'plaintext' => $_POST['plaintext'],
                    'transport' => $_POST['transport'],
                    'priority' => $_POST['priority']
                ], 
                [['id', '=', $letter_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_update_letter', [
                'id' => $letter_id,
                'sender' => $_POST['sender'],
                'subject' => $_POST['subject'],
                'html' => $_POST['html'],
                'plaintext' => $_POST['plaintext'],
                'transport' => $_POST['transport'],
                'priority' => $_POST['priority']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateSender() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $sender_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders',
            [['id', '=', $sender_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['email'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'mail_senders', 
                [
                    'name' => $_POST['name'],
                    'email' => $_POST['email']
                ], 
                [['id', '=', $sender_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_update_sender', [
                'id' => $sender_id,
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIAddLettersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $sub_id = $_POST['sub_id'] ? APP::Module('Crypt')->Decode($_POST['sub_id']) : 0;

        if ($sub_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'mail_letters_groups',
                [['id', '=', $sub_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }
        
        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if ($out['status'] == 'success') {
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_letters_groups',
                Array(
                    'id' => 'NULL',
                    'sub_id' => [$sub_id, PDO::PARAM_INT],
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('mail_add_letters_group', [
                'id' => $out['group_id'],
                'sub_id' => $sub_id,
                'name' => $_POST['name']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIAddSendersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $sub_id = $_POST['sub_id'] ? APP::Module('Crypt')->Decode($_POST['sub_id']) : 0;

        if ($sub_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'mail_senders_groups',
                [['id', '=', $sub_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }
        
        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if ($out['status'] == 'success') {
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_senders_groups',
                Array(
                    'id' => 'NULL',
                    'sub_id' => [$sub_id, PDO::PARAM_INT],
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('mail_add_senders_group', [
                'id' => $out['group_id'],
                'sub_id' => $sub_id,
                'name' => $_POST['name']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveLettersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_letters_groups',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $this->RemoveLettersGroup($_POST['id']);
            APP::Module('Triggers')->Exec('mail_remove_letters_group', ['id' => $_POST['id']]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveSendersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders_groups',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $this->RemoveSendersGroup($_POST['id']);
            APP::Module('Triggers')->Exec('mail_remove_senders_group', ['id' => $_POST['id']]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateLettersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_letters_groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'mail_letters_groups', 
                ['name' => $_POST['name']], 
                [['id', '=', $group_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_update_letters_group', [
                'id' => $group_id,
                'name' => $_POST['name']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIManageLetters() {
        $group_sub_id = $_POST['group'] ? APP::Module('Crypt')->Decode($_POST['group']) : 0;
        $path = [];
        $list = [];

        foreach ($this->RenderLettersGroupsPath($group_sub_id) as $key => $value) {
            $path[] = [$key ? APP::Module('Crypt')->Encode($key) : 0, $value];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name'], 'mail_letters_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', APP::Module('Crypt')->Encode($value['id']), $value['name']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'subject'], 'mail_letters',
            [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['letter', APP::Module('Crypt')->Encode($value['id']), $value['id'], $value['subject']];
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'group_sub_id' => $group_sub_id,
            'path' => $path,
            'list' => $list
        ]);
    }
    
    public function APIUpdateSendersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_senders_groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'mail_senders_groups', 
                ['name' => $_POST['name']], 
                [['id', '=', $group_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_update_senders_group', [
                'id' => $group_id,
                'name' => $_POST['name']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_db_connection']], [['item', '=', 'module_mail_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_tmp_dir']], [['item', '=', 'module_mail_tmp_dir', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_x_mailer']], [['item', '=', 'module_mail_x_mailer', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_charset']], [['item', '=', 'module_mail_charset', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => isset($_POST['module_mail_save_sent_email'])], [['item', '=', 'module_mail_save_sent_email', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_sent_email_lifetime']], [['item', '=', 'module_mail_sent_email_lifetime', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('mail_update_settings', [
            'db_connection' => $_POST['module_mail_db_connection'],
            'tmp_dir' => $_POST['module_mail_tmp_dir'],
            'x_mailer' => $_POST['module_mail_x_mailer'],
            'charset' => $_POST['module_mail_charset'],
            'save_sent_email' => isset($_POST['module_mail_save_sent_email']),
            'sent_email_lifetime' => $_POST['module_mail_sent_email_lifetime']
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
    
    public function APIListTransports() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'module', 'method', 'settings'], 'mail_transport',
            $_POST['searchPhrase'] ? [['module', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
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
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_transport', $_POST['searchPhrase'] ? [['module', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIAddTransport() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['module'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['method'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['settings'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if ($out['status'] == 'success') {
            $out['transport_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], ' mail_transport',
                [
                    'id' => 'NULL',
                    'module' => [$_POST['module'], PDO::PARAM_STR],
                    'method' => [$_POST['method'], PDO::PARAM_STR],
                    'settings' => [$_POST['settings'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );;
        
            APP::Module('Triggers')->Exec('mail_add_transport', [
                'id' => $out['transport_id'],
                'module' => $_POST['module'],
                'method' => $_POST['method'],
                'settings' => $_POST['settings']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateTransport() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $transport_id = APP::Module('Crypt')->Decode($_POST['transport_id']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_transport',
            [['id', '=', $transport_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['module'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['method'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['settings'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_mail_db_connection'], 'mail_transport', [
                'module' => $_POST['module'],
                'method' => $_POST['method'],
                'settings' => $_POST['settings']
            ], [['id', '=', $transport_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('mail_update_transport', [
                'id' => $transport_id,
                'module' => $_POST['module'],
                'method' => $_POST['method'],
                'settings' => $_POST['settings']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveTransport() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_transport',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_mail_db_connection'], 'mail_transport', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_transport', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    
    
    
    public function APIListShortcodes() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'code', 'content'], 'mail_shortcodes',
            $_POST['searchPhrase'] ? [['code', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            false, false, false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
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
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_shortcodes', $_POST['searchPhrase'] ? [['code', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIAddShortcode() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['code'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['value'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if ($out['status'] == 'success') {
            $out['shortcode_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], ' mail_shortcodes',
                [
                    'id' => 'NULL',
                    'code' => [$_POST['code'], PDO::PARAM_STR],
                    'content' => [$_POST['value'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                ]
            );;
        
            APP::Module('Triggers')->Exec('mail_add_shortcode', [
                'id' => $out['shortcode_id'],
                'code' => $_POST['code'],
                'content' => $_POST['value']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateShortcode() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $shortcode_id = APP::Module('Crypt')->Decode($_POST['shortcode_id']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_shortcodes',
            [['id', '=', $shortcode_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['code'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['value'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_mail_db_connection'], 'mail_shortcodes', [
                'code' => $_POST['code'],
                'content' => $_POST['value']
            ], [['id', '=', $shortcode_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('mail_update_shortcode', [
                'id' => $shortcode_id,
                'code' => $_POST['code'],
                'content' => $_POST['value']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveShortcode() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_shortcodes',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_mail_db_connection'], 'mail_shortcodes', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_shortcode', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    
    
    
    public function APIListLog() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'mail_log.id', 
                'mail_log.user', 
                'mail_log.letter', 
                'mail_log.sender', 
                'mail_log.transport', 
                'mail_log.state', 
                'mail_log.result', 
                'mail_log.retries', 
                'mail_log.ping',
                'mail_log.cr_date',
                'users.email',
                'mail_letters.subject',
                'mail_letters.group_id AS letter_group',
                'mail_senders.group_id AS sender_group',
                'mail_senders.name AS sender_name',
                'mail_senders.email AS sender_email',
                'mail_transport.module AS transport_module',
                'mail_transport.method AS transport_method',
                'mail_transport.settings AS transport_settings',
                'COUNT(mail_events.id) AS events',
                'COUNT(mail_copies.id) AS copies'
            ], 'mail_log',
            $_POST['searchPhrase'] ? [['user', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            [
                'join/users' => [['mail_log.user', '=', 'users.id']],
                'join/mail_letters' => [['mail_log.letter', '=', 'mail_letters.id']],
                'join/mail_senders' => [['mail_log.sender', '=', 'mail_senders.id']],
                'join/mail_transport' => [['mail_log.transport', '=', 'mail_transport.id']],
                'left join/mail_events' => [['mail_log.id', '=', 'mail_events.log']],
                'left join/mail_copies' => [['mail_log.id', '=', 'mail_copies.log']]
            ],
            ['mail_log.id'], 
            false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            $row['user_token'] = APP::Module('Crypt')->Encode($row['user']);
            $row['letter'] = [$row['letter'], APP::Module('Crypt')->Encode($row['letter'])];
            $row['sender'] = [$row['sender'], APP::Module('Crypt')->Encode($row['sender'])];
            $row['letter_group'] = [$row['letter_group'], $row['letter_group'] ? APP::Module('Crypt')->Encode($row['letter_group']) : 0];
            $row['sender_group'] = [$row['sender_group'], $row['sender_group'] ? APP::Module('Crypt')->Encode($row['sender_group']) : 0];
            
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_log', $_POST['searchPhrase'] ? [['user', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIRemoveLogEntry() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_log',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_mail_db_connection'], 'mail_log', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_log_entry', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIListQueue() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'mail_queue.id', 
                'mail_queue.log',
                'mail_queue.user', 
                'mail_queue.letter', 
                'mail_queue.sender', 
                'mail_queue.transport',
                'mail_queue.result', 
                'mail_queue.retries', 
                'mail_queue.ping',
                'mail_queue.priority',
                'mail_queue.token',
                'mail_queue.execute',
                'mail_queue.subject',
                'mail_queue.sender_name',
                'mail_queue.sender_email',
                'mail_queue.recepient',
                'mail_letters.group_id AS letter_group',
                'mail_senders.group_id AS sender_group',
                'mail_transport.module AS transport_module',
                'mail_transport.method AS transport_method',
                'mail_transport.settings AS transport_settings',
                'COUNT(mail_copies.id) AS copies'
            ], 'mail_queue',
            $_POST['searchPhrase'] ? [['user', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            [
                'join/mail_letters' => [['mail_queue.letter', '=', 'mail_letters.id']],
                'join/mail_senders' => [['mail_queue.sender', '=', 'mail_senders.id']],
                'join/mail_transport' => [['mail_queue.transport', '=', 'mail_transport.id']],
                'left join/mail_copies' => [['mail_queue.log', '=', 'mail_copies.log']]
            ],
            ['mail_queue.id'], 
            false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            $row['log_token'] = APP::Module('Crypt')->Encode($row['log']);
            $row['user_token'] = APP::Module('Crypt')->Encode($row['user']);
            $row['letter'] = [$row['letter'], APP::Module('Crypt')->Encode($row['letter'])];
            $row['sender'] = [$row['sender'], APP::Module('Crypt')->Encode($row['sender'])];
            $row['letter_group'] = [$row['letter_group'], $row['letter_group'] ? APP::Module('Crypt')->Encode($row['letter_group']) : 0];
            $row['sender_group'] = [$row['sender_group'], $row['sender_group'] ? APP::Module('Crypt')->Encode($row['sender_group']) : 0];
            
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_queue', $_POST['searchPhrase'] ? [['recepient', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIRemoveQueueEntry() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_queue',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_mail_db_connection'], 'mail_queue', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_queue_entry', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIListEvents() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode(APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['*'], 'mail_events',
            [['log', '=', APP::Module('Crypt')->Decode($_POST['token']), PDO::PARAM_INT]],
            false, false, false, 
            ['cr_date', 'desc']
        ));
        exit;
    }
    
    public function APIGetSenders() {
        $out = [];

        foreach ((array) $_POST['where'] as $key => $value) {
            $_POST['where'][$key][] = PDO::PARAM_STR;
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            $_POST['select'], 'mail_senders', 
            $_POST['where']
        ) as $value) {
            //$value['token'] = APP::Module('Crypt')->Encode($value['id']);
            $out[] = $value;
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
    }
    
    public function APIGetLetters() {
        $out = [];

        foreach ((array) $_POST['where'] as $key => $value) {
            $_POST['where'][$key][] = PDO::PARAM_STR;
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            $_POST['select'], 'mail_letters', 
            $_POST['where']
        ) as $value) {
            $value['token'] = APP::Module('Crypt')->Encode($value['id']);
            $out[] = $value;
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
    }

    public function APIAddIPSpamLists() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['ip'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['ip_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'mail_ip',
                [
                    'id' => 'NULL',
                    'addr' => [$_POST['ip'], PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                ]
            );
        
            APP::Module('Triggers')->Exec('mail_add_ip_spamlist', [
                'id' => $out['ip_id'],
                'ip' => $_POST['ip']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIGetIPSpamLists() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'mail_ip.id', 
                'mail_ip.addr',
                'COUNT(mail_ip_status.id) AS spam_lists'
            ], 
            'mail_ip',
            $_POST['searchPhrase'] ? [['mail_ip.addr', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false, 
            [
                'left join/mail_ip_status' => [
                    ['mail_ip.id', '=', 'mail_ip_status.ip'],
                    ['"wrong"', '=', 'mail_ip_status.state']
                ]
            ],
            ['mail_ip.id'], false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            $_POST['rowCount'] == -1 ? false : [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
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
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_ip', $_POST['searchPhrase'] ? [['addr', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIRemoveIPSpamLists() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'mail_ip',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete($this->settings['module_mail_db_connection'], 'mail_ip', [['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_ip_spam_list', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIStatusIPSpamLists() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'service',
                'state',
                'UNIX_TIMESTAMP(cr_date) AS cr_date',
            ], 
            'mail_ip_status',
            $_POST['searchPhrase'] ? [
                ['ip', '=', APP::Module('Routing')->get['ip'], PDO::PARAM_STR],
                ['service', 'LIKE', $_POST['searchPhrase'] . '%' ]
            ] : [
                ['ip', '=', APP::Module('Routing')->get['ip'], PDO::PARAM_STR],
            ], 
            false, false, false,
            [array_keys($_POST['sort'])[0], array_values($_POST['sort'])[0]],
            [($_POST['current'] - 1) * $_POST['rowCount'], $_POST['rowCount']]
        ) as $row) {
            $row['date'] = date(DATE_RFC822, $row['cr_date']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_mail_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'mail_ip_status', $_POST['searchPhrase'] ? [['service', 'LIKE', $_POST['searchPhrase'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APIFBLReportsDashboard() {
        $out = [];
        
        foreach ($this->fbl_folders as $folder) {
            $folder_out = [];
            
            for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
                $folder_out[date('d-m-Y', $x)] = 0;
            }

            foreach (APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                ['users', 'UNIX_TIMESTAMP(cr_date) AS cr_date'], 'mail_fbl_log',
                [
                    ['service', '=', $folder, PDO::PARAM_STR],
                    ['UNIX_TIMESTAMP(cr_date)', 'BETWEEN', $_POST['date']['from'] . ' AND ' . $_POST['date']['to']]
                ]
            ) as $fbl) {
                $folder_out[date('d-m-Y', $fbl['cr_date'])] = $folder_out[date('d-m-Y', $fbl['cr_date'])] + $fbl['users'];
            }

            foreach ($folder_out as $key => $value) {
                $folder_out[$key] = [strtotime($key) * 1000, $value];
            }
            
            $out[$folder] = array_values($folder_out);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
    }
    
    public function APILogDashboard() {
        $out = [];
        
        foreach (['wait', 'error', 'success'] as $state) {
            $state_out = [];
            
            for ($x = $_POST['date']['to']; $x >= $_POST['date']['from']; $x = $x - 86400) {
                $state_out[date('d-m-Y', $x)] = 0;
            }

            foreach (APP::Module('DB')->Select(
                $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                ['UNIX_TIMESTAMP(cr_date) AS cr_date'], 'mail_log',
                [
                    ['state', '=', $state, PDO::PARAM_STR],
                    ['UNIX_TIMESTAMP(cr_date)', 'BETWEEN', $_POST['date']['from'] . ' AND ' . $_POST['date']['to']]
                ]
            ) as $date) {
                ++ $state_out[date('d-m-Y', $date)];
            }

            foreach ($state_out as $key => $value) {
                $state_out[$key] = [strtotime($key) * 1000, $value];
            }
            
            $out[$state] = array_values($state_out);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
    }
    
    public function APIStatDashboard() {
        $form = Array(
            'mode' => 'extend',
            'sender' => Array(),
            'mailing' => Array(),
            'letter' => Array()
        );
        
        if (isset($_POST['form']['mode'])) $form['mode'] = $_POST['form']['mode'];
        if (isset($_POST['form']['date'])) $form['date'] = $_POST['form']['date'];
        if ((isset($_POST['form']['sender'])) && ($_POST['form']['sender'] != '')) $form['sender'] = $_POST['form']['sender'];
        if ((isset($_POST['form']['letter'])) && ($_POST['form']['letter'] != '')) $form['letter'] = $_POST['form']['letter'];
        
        $where = Array('UNIX_TIMESTAMP(cr_date) BETWEEN ' . $_POST['date']['from'] . ' AND ' . $_POST['date']['to']);
        
        if (count($form['sender'])) $where[] = 'sender IN (' . implode(',', $form['sender']) . ')';
        if (count($form['letter'])) $where[] = 'letter IN (' . implode(',', $form['letter']) . ')';
        
        $select = Array(
            'mail_events.letter',
            'mail_events.event'
        );
        
        if ($form['mode'] == 'extend') {
            $select[] = 'mail_events.token';
        }

        $read = APP::Module('DB')->Open($this->settings['module_mail_db_connection'])->prepare('
            SELECT ' . implode(',', $select) . '
            FROM mail_events 
            WHERE 
                mail_events.event != "deferred" && 
                mail_events.log IN (
                    SELECT mail_log.id 
                    FROM mail_log 
                    WHERE 
                        ' . implode(' && ', $where) . '
                )
        ');

        $read->execute();
        $events_list = $read->fetchAll(PDO::FETCH_ASSOC);

        $letters = Array();

        foreach ($events_list as $event) {
            switch ($event['event']) {
                case 'delivered':
                case 'processed':
                case 'open':
                case 'click':
                case 'unsubscribe':
                case 'spamreport':
                    $letters[$event['event']][] = $event['letter'];
                    break;
                case 'dropped':
                case 'bounce':
                    switch ($form['mode']) {
                        case 'default': $letters[$event['event']][] = $event['letter']; break;
                        case 'extend': $letters[$event['event']][$event['token']][] = $event['letter']; break;
                    }
                    break;
            }
        }

        $stat = Array();
        
        foreach ($letters as $event => $letters_id) {
            switch ($event) {
                case 'delivered':
                case 'processed':
                case 'open':
                case 'click':
                case 'unsubscribe':
                case 'spamreport':
                    $stat[$event] = count(array_unique($letters_id));
                    break;
                case 'dropped':
                case 'bounce':
                    switch ($form['mode']) {
                        case 'default': 
                            $stat[$event] = count(array_unique($letters_id)); 
                            break;
                        case 'extend':
                            foreach ($letters_id as $status => $values) {
                                $status_name = $status;
                            
                                if ($event == 'bounce') {
                                    switch ($status) {
                                        case '421': $status_name = 'Service not available, closing transmission channel'; break;
                                        case '450': $status_name = 'Requested mail action not taken: mailbox unavailable (e.g., mailbox busy)'; break;
                                        case '451': $status_name = 'Requested action aborted: error in processing'; break;
                                        case '452': $status_name = 'Requested action not taken: insufficient system storage'; break;

                                        case '500': $status_name = 'The server could not recognize the command due to a syntax error'; break;
                                        case '501': $status_name = 'A syntax error was encountered in command arguments'; break;
                                        case '502': $status_name = 'This command is not implemented'; break;
                                        case '503': $status_name = 'The server has encountered a bad sequence of commands'; break;
                                        case '504': $status_name = 'A command parameter is not implemented'; break;
                                        case '550': $status_name = 'User’s mailbox was unavailable (such as not found)'; break;
                                        case '551': $status_name = 'The recipient is not local to the server'; break;
                                        case '552': $status_name = 'The action was aborted due to exceeded storage allocation'; break;
                                        case '553': $status_name = 'The command was aborted because the mailbox name is invalid'; break;
                                        case '554': $status_name = 'The transaction failed for some unstated reason'; break;

                                        case '4.2.1': $status_name = 'Other Address Status'; break;
                                        case '4.2.2': $status_name = 'Bad mailbox address'; break;
                                        case '4.2.3': $status_name = 'Bad system address'; break;
                                        case '4.2.4': $status_name = 'Bad mailbox address syntax'; break;
                                        case '4.2.5': $status_name = 'Mailbox address ambiguous'; break;
                                        case '4.2.6': $status_name = 'Address Valid'; break;
                                        case '4.2.7': $status_name = 'Mailbox has moved, No forwarding address'; break;
                                        case '4.3.1': $status_name = 'Other or undefined mailbox status'; break;
                                        case '4.3.2': $status_name = 'Mailbox disabled, not accepting messages'; break;
                                        case '4.3.3': $status_name = 'Mailbox full'; break;
                                        case '4.3.4': $status_name = 'Message length exceeds administrative limit'; break;
                                        case '4.3.5': $status_name = 'Mailing list expansion problem'; break;
                                        case '4.3.6': $status_name = 'System Status'; break;
                                        case '4.3.7': $status_name = 'Other or undefined system status'; break;
                                        case '4.3.8': $status_name = 'System full'; break;
                                        case '4.3.9': $status_name = 'System not accepting network messages'; break;
                                        case '4.3.10': $status_name = 'System not capable of selected features'; break;
                                        case '4.3.11': $status_name = 'Message too big for system'; break;
                                        case '4.4.1': $status_name = 'Other or undefined network or routing status'; break;
                                        case '4.4.2': $status_name = 'No answer from host'; break;
                                        case '4.4.3': $status_name = 'Bad connection'; break;
                                        case '4.4.4': $status_name = 'Routing server failure'; break;
                                        case '4.4.5': $status_name = 'Unable to route'; break;
                                        case '4.4.6': $status_name = 'Network congestion'; break;
                                        case '4.4.7': $status_name = 'Routing loop detected'; break;
                                        case '4.4.8': $status_name = 'Delivery time expired'; break;
                                        case '4.5.1': $status_name = 'Other or undefined protocol status'; break;
                                        case '4.5.2': $status_name = 'Invalid command'; break;
                                        case '4.5.3': $status_name = 'Syntax error'; break;
                                        case '4.5.4': $status_name = 'Too many recipients'; break;
                                        case '4.5.5': $status_name = 'Invalid command arguments'; break;
                                        case '4.5.6': $status_name = 'Wrong protocol version'; break;
                                        case '4.6.1': $status_name = 'Other or undefined media error'; break;
                                        case '4.6.2': $status_name = 'Media not supported'; break;
                                        case '4.6.3': $status_name = 'Conversion required and prohibited'; break;
                                        case '4.6.4': $status_name = 'Conversion required but not supported'; break;
                                        case '4.6.5': $status_name = 'Conversion with loss performed'; break;
                                        case '4.6.6': $status_name = 'Conversion Failed'; break;
                                        case '4.7.1': $status_name = 'Other or undefined security status'; break;
                                        case '4.7.2': $status_name = 'Delivery not authorized, message refused'; break;
                                        case '4.7.3': $status_name = 'Mailing list expansion prohibited'; break;
                                        case '4.7.4': $status_name = 'Security conversion required but not possible'; break;
                                        case '4.7.5': $status_name = 'Security features not supported'; break;
                                        case '4.7.6': $status_name = 'Cryptographic failure'; break;
                                        case '4.7.7': $status_name = 'Cryptographic algorithm not supported'; break;
                                        case '4.7.8': $status_name = 'Message integrity failure'; break;

                                        case '5.0.0': $status_name = 'Address does not exist'; break;
                                        case '5.1.0': $status_name = 'Other address status'; break;
                                        case '5.1.1': $status_name = 'Bad destination mailbox address'; break;
                                        case '5.1.2': $status_name = 'Bad destination system address'; break;
                                        case '5.1.3': $status_name = 'Bad destination mailbox address syntax'; break;
                                        case '5.1.4': $status_name = 'Destination mailbox address ambiguous'; break;
                                        case '5.1.5': $status_name = 'Destination mailbox address valid'; break;
                                        case '5.1.6': $status_name = 'Mailbox has moved'; break;
                                        case '5.1.7': $status_name = 'Bad sender’s mailbox address syntax'; break;
                                        case '5.1.8': $status_name = 'Bad sender’s system address'; break;
                                        case '5.2.0': $status_name = 'Other or undefined mailbox status'; break;
                                        case '5.2.1': $status_name = 'Mailbox disabled, not accepting messages'; break;
                                        case '5.2.2': $status_name = 'Mailbox full'; break;
                                        case '5.2.3': $status_name = 'Message length exceeds administrative limit'; break;
                                        case '5.2.4': $status_name = 'Mailing list expansion problem'; break;
                                        case '5.3.0': $status_name = 'Other or undefined mail system status'; break;
                                        case '5.3.1': $status_name = 'Mail system full'; break;
                                        case '5.3.2': $status_name = 'System not accepting network messages'; break;
                                        case '5.3.3': $status_name = 'System not capable of selected features'; break;
                                        case '5.3.4': $status_name = 'Message too big for system'; break;
                                        case '5.4.0': $status_name = 'Other or undefined network or routing status'; break;
                                        case '5.4.1': $status_name = 'No answer from host'; break;
                                        case '5.4.2': $status_name = 'Bad connection'; break;
                                        case '5.4.3': $status_name = 'Routing server failure'; break;
                                        case '5.4.4': $status_name = 'Unable to route'; break;
                                        case '5.4.5': $status_name = 'Network congestion'; break;
                                        case '5.4.6': $status_name = 'Routing loop detected'; break;
                                        case '5.4.7': $status_name = 'Delivery time expired'; break;
                                        case '5.5.0': $status_name = 'Other or undefined protocol status'; break;
                                        case '5.5.1': $status_name = 'Invalid command'; break;
                                        case '5.5.2': $status_name = 'Syntax error'; break;
                                        case '5.5.3': $status_name = 'Too many recipients'; break;
                                        case '5.5.4': $status_name = 'Invalid command arguments'; break;
                                        case '5.5.5': $status_name = 'Wrong protocol version'; break;
                                        case '5.6.0': $status_name = 'Other or undefined media error'; break;
                                        case '5.6.1': $status_name = 'Media not supported'; break;
                                        case '5.6.2': $status_name = 'Conversion required and prohibited'; break;
                                        case '5.6.3': $status_name = 'Conversion required but not supported'; break;
                                        case '5.6.4': $status_name = 'Conversion with loss performed'; break;
                                        case '5.6.5': $status_name = 'Conversion failed'; break;
                                        case '5.7.0': $status_name = 'Other or undefined security status'; break;
                                        case '5.7.1': $status_name = 'Delivery not authorized, message refused'; break;
                                        case '5.7.2': $status_name = 'Mailing list expansion prohibited'; break;
                                        case '5.7.3': $status_name = 'Security conversion required but not possible'; break;
                                        case '5.7.4': $status_name = 'Security features not supported'; break;
                                        case '5.7.5': $status_name = 'Cryptographic failure'; break;
                                        case '5.7.6': $status_name = 'Cryptographic algorithm not supported'; break;
                                        case '5.7.7': $status_name = 'Message integrity failure'; break;
                                    }
                                }
                                
                                $stat[$event][$status_name] = count(array_unique($values));
                            }
                            break;
                    }

                    break;
            }
        }
        
        $bounce_count = 0;
        $dropped_count = 0;
        
        if (isset($stat['bounce'])) {
            foreach ($stat['bounce'] as $values) {
                $bounce_count += $values;
            }
        }
        
        if (isset($stat['dropped'])) {
            foreach ($stat['dropped'] as $values) {
                $dropped_count += $values;
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'bounce' => $bounce_count,
            'dropped' => $dropped_count,
            'unsubscribe' => isset($stat['unsubscribe']) ? $stat['unsubscribe'] : false,
            'spamreport' => isset($stat['spamreport']) ? $stat['spamreport'] : false,
            'click' => isset($stat['click']) ? $stat['click'] : false,
            'open' => isset($stat['open']) ? $stat['open'] : false,
            'delivered' => isset($stat['delivered']) ? $stat['delivered'] : false,
            'processed' => isset($stat['processed']) ? $stat['processed'] : false,
            'details' => $stat,
            'count_events' => count($events_list),
            'form' => $form,
            'hash' => Array(
                'bounce' => '',
                'click' => '',
                'delivered' => '',
                'dropped' => '',
                'open' => '',
                'processed' => '',
                'spamreport' => '',
                'unsubscribe' => ''
            )
        ]);
    }
    
    
    public function Spamreport() {
        $mail_log = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['mail_log_hash']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        )) {
            APP::Render(
                'mail/spamreport', 'include', 
                [
                    'result' => false,
                ]
            );
            exit;
        }

        $mail = APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['user', 'letter'], 'mail_log',
            [['id', '=', $mail_log, PDO::PARAM_INT]]
        );

        if (APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['value'], 'users_about',
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        ) == 'blacklist') {
            APP::Render(
                'mail/spamreport', 'include', 
                [
                    'result' => true,
                ]
            );
            exit;
        }
        
        APP::Module('DB')->Insert(
            APP::Module('Users')->settings['module_users_db_connection'], 'users_tags',
            [
                'id' => 'NULL',
                'user' => [$mail['user'], PDO::PARAM_INT],
                'item' => ['spamreport', PDO::PARAM_STR],
                'value' => [json_encode([
                    'item' => 'mail',
                    'id' => $mail_log
                ]), PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('DB')->Update(
            APP::Module('Users')->settings['module_users_db_connection'], 'users_about', 
            [
                'value' => 'blacklist'
            ], 
            [
                ['user', '=', $mail['user'], PDO::PARAM_INT],
                ['item', '=', 'state', PDO::PARAM_STR]
            ]
        );
        
        APP::Module('DB')->Insert(
            $this->settings['module_mail_db_connection'], 'mail_events',
            [
                'id' => 'NULL',
                'log' => [$mail_log, PDO::PARAM_INT],
                'user' => [$mail['user'], PDO::PARAM_INT],
                'letter' => [$mail['letter'], PDO::PARAM_INT],
                'event' => ['spamreport', PDO::PARAM_STR],
                'details' => 'NULL',
                'token' => [$mail_log, PDO::PARAM_STR],
                'cr_date' => 'NOW()'
            ]
        );
        
        $this->Send(
            APP::Module('DB')->Select(
                APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['email'], 'users',
                [['id', '=', $mail['user'], PDO::PARAM_INT]]
            ),
            APP::Module('Users')->settings['module_users_subscription_restore_letter']
        );
        
        APP::Module('Triggers')->Exec('mail_spamreport', [
            'user' => $mail['user'],
            'label' => 'spamreport'
        ]);
        
        APP::Render(
            'mail/spamreport', 'include', 
            [
                'result' => true,
            ]
        );
    }
    
}