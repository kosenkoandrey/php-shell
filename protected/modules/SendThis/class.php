<?
class SendThis {

    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_sendthis_ssh_connection',
            'module_sendthis_version',
            'module_sendthis_key',
            'module_sendthis_tmp_dir'
        ]);
    }
    
    public function Admin() {
        return APP::Render('sendthis/admin/nav', 'content');
    }
    
    
    public function DefaultTransport($recepient, $letter, $params) {
        $user = (int) APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetchColumn', 0], 
            ['id'], 'users', [['email', '=', $recepient, PDO::PARAM_STR]]
        );
        
        if (!$user) {
            return [
                'result' => false, 
                'id' => 0
            ];
        }

        $id = APP::Module('DB')->Insert(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_log',
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
        
        if (!$id) {
            return [
                'result' => false, 
                'id' => 0
            ];
        }

        $letter_hash = APP::Module('Crypt')->Encode($id);
        
        $letter['subject'] = str_replace('[letter_hash]', $letter_hash, $letter['subject']);
        $letter['html'] = str_replace('[letter_hash]', $letter_hash, $letter['html']);
        $letter['plaintext'] = str_replace('[letter_hash]', $letter_hash, $letter['plaintext']);

        if (APP::Module('Mail')->settings['module_mail_save_sent_email']) {
            APP::Module('DB')->Insert(
                APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_copies',
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

        $sender = APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['name', 'email'], 'mail_senders',
            [['id', '=', $letter['sender'], PDO::PARAM_INT]]
        );

        $curl = APP::Module('Utils')->Curl([
            'url' => 'http://sendthis.ru/api/' . $this->settings['module_sendthis_version'] . '/sendmail.php',
            'return_transfer' => true,
            'post' => [
                'key' => $this->settings['module_sendthis_key'],
                'from' => $sender,
                'to' => $recepient,
                'subject' => $letter['subject'],
                'message' => [
                    'html' => $letter['html'],
                    'text' => $letter['plaintext']
                ],
                'params' => json_encode(['id' => $id, 'extra' => $params], JSON_PARTIAL_OUTPUT_ON_ERROR),
                'list_id' => $letter['group_id']
            ]
        ], 'resource');
        
        $result = json_decode(curl_exec($curl), true);

        APP::Module('DB')->Update(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_log', 
            [
                'state' => $result['status'],
                'result' => $result['info'],
                'retries' => 1,
                'ping' => curl_getinfo($curl, CURLINFO_TOTAL_TIME)
            ], 
            [['id', '=', $id, PDO::PARAM_INT]]
        );
        
        curl_close($curl);

        return [
            'result' => true, 
            'id' => $id
        ];
    }
    
    public function DaemonTransport($recepient, $letter, $params) {
        $user = (int) APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetchColumn', 0], 
            ['id'], 'users', [['email', '=', $recepient, PDO::PARAM_STR]]
        );
        
        if (!$user) {
            return [
                'result' => false, 
                'id' => 0
            ];
        }

        $id = APP::Module('DB')->Insert(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_log',
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
        
        if (!$id) {
            return [
                'result' => false, 
                'id' => 0
            ];
        }

        $letter_hash = APP::Module('Crypt')->Encode($id);
        
        $letter['subject'] = str_replace('[letter_hash]', $letter_hash, $letter['subject']);
        $letter['html'] = str_replace('[letter_hash]', $letter_hash, $letter['html']);
        $letter['plaintext'] = str_replace('[letter_hash]', $letter_hash, $letter['plaintext']);

        if (APP::Module('Mail')->settings['module_mail_save_sent_email']) {
            APP::Module('DB')->Insert(
                APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_copies',
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

        $sender = APP::Module('DB')->Select(
            APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['name', 'email'], 'mail_senders',
            [['id', '=', $letter['sender'], PDO::PARAM_INT]]
        );

        APP::Module('DB')->Insert(
            APP::Module('Mail')->settings['module_mail_db_connection'], 'mail_queue',
            [
                'id' => 'NULL',
                'log' => [$id, PDO::PARAM_INT],
                'user' => [$user, PDO::PARAM_INT],
                'letter' => [$letter['id'], PDO::PARAM_INT],
                'sender' => [$letter['sender'], PDO::PARAM_INT],
                'transport' => [$letter['transport'], PDO::PARAM_STR],
                'sender_name' => [$sender['name'], PDO::PARAM_STR],
                'sender_email' => [$sender['email'], PDO::PARAM_STR],
                'recepient' => [$recepient, PDO::PARAM_STR],
                'subject' => [$letter['subject'], PDO::PARAM_STR],
                'html' => [$letter['html'], PDO::PARAM_STR],
                'plaintext' => [$letter['plaintext'], PDO::PARAM_STR],
                'retries' => 0,
                'ping' => 0,
                'execute' => [isset($params['execute']) ? $params['execute'] : date('Y-m-d H:i:s'), PDO::PARAM_STR],
                'priority' => [$letter['priority'], PDO::PARAM_INT],
                'result' => 'NULL',
                'token' => ['sendthis', PDO::PARAM_STR]
            ]
        );

        return [
            'result' => true, 
            'id' => $id
        ];
    }

    
    public function Webhooks() {
        $mail_events_log = fopen($this->settings['module_sendthis_tmp_dir'] . '/mail_events_' . md5(implode(APP::$conf['location'])), 'a');
        flock($mail_events_log, LOCK_EX);
        
        foreach ((array) $_POST['tasks'] as $task) {
            $mail = APP::Module('DB')->Select(
                APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'user', 'letter'], 'mail_log', [['id', '=', $task['params']['id'], PDO::PARAM_STR]]
            );

            $token = false;

            switch ($task['event']) {
                case 'processed': 
                    APP::Module('Triggers')->Exec('mail_event_processed', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'delivered': 
                    APP::Module('Triggers')->Exec('mail_event_delivered', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'deferred': 
                    APP::Module('Triggers')->Exec('mail_event_deferred', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'bounce': 
                    $token = $task['dsn'];

                    if ((int) $token{0} === 5) {
                        APP::Module('Triggers')->Exec('mail_event_bounce_hard', [
                            'id' => $mail['id'],
                            'task' => $task
                        ]);
                    } else {
                        APP::Module('Triggers')->Exec('mail_event_bounce_soft', [
                            'id' => $mail['id'],
                            'task' => $task
                        ]);
                    }
                    break;
                case 'unsubscribe': 
                    APP::Module('Triggers')->Exec('mail_event_unsubscribe', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'spamreport': 
                    APP::Module('Triggers')->Exec('mail_event_spamreport', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'open': 
                    APP::Module('Triggers')->Exec('mail_event_open', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
                case 'click':
                    $token = $task['url'];
                    /*
                    if (!APP::Module('DB')->Select(
                        APP::Module('Mail')->settings['module_mail_db_connection'], ['fetchColumn', 0], 
                        ['COUNT(id)'], 'mail_events', [
                            ['log', '=', $mail['id'], PDO::PARAM_INT],
                            ['event', '=', 'open', PDO::PARAM_STR]
                        ]
                    )) {
                        fwrite($mail_events_log, "NULL\t" . $mail['id'] . "\t" . $mail['user'] . "\t" . $mail['letter'] . "\topen\t{}\t\t" . date('Y-m-d H:i:s', $task['timestamp']) . "\n");

                        APP::Module('Triggers')->Exec('mail_event_open', [
                            'id' => $mail['id']
                        ]);
                    }
                     * 
                     */

                    APP::Module('Triggers')->Exec('click', [
                        'id' => $mail['id'],
                        'task' => $task
                    ]);
                    break;
            }

            fwrite($mail_events_log, "NULL\t" . $mail['id'] . "\t" . $mail['user'] . "\t" . $mail['letter'] . "\t" . $task['event'] . "\t" . json_encode($task) . "\t" . $token . "\t" . date('Y-m-d H:i:s', $task['timestamp']) . "\n");
        }
        
        flock($mail_events_log, LOCK_UN);
        fclose($mail_events_log);

        APP::Module('Triggers')->Exec('sendthis_webhook', $_POST);
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode(['message' => 'success']);
        exit;
    }
    
    public function LoadWebhooks() {
        $lock = fopen($this->settings['module_sendthis_tmp_dir'] . '/module_sendthis_load_webhooks.lock', 'w'); 
        
        if (flock($lock, LOCK_EX|LOCK_NB)) { 
            APP::Module('DB')->Open(APP::Module('Mail')->settings['module_mail_db_connection'])->query('LOAD DATA INFILE \'' . $this->settings['module_sendthis_tmp_dir'] . '/mail_events_' . md5(implode(APP::$conf['location'])) . '\' INTO TABLE mail_events');
            exec('rm ' . $this->settings['module_sendthis_tmp_dir'] . '/mail_events_' . md5(implode(APP::$conf['location'])));
        } else { 
            exit;
        } 
        
        fclose($lock);
    }
    
    
    public function Settings() {
        APP::Render('sendthis/admin/index');
    }
    
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_sendthis_version']], [['item', '=', 'module_sendthis_version', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_sendthis_key']], [['item', '=', 'module_sendthis_key', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_sendthis_tmp_dir']], [['item', '=', 'module_sendthis_tmp_dir', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('sendthis_update_settings', [
            'version' => $_POST['module_sendthis_version'],
            'key' => $_POST['module_sendthis_key'],
            'tmp_dir' => $_POST['module_sendthis_tmp_dir']
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