<?
class Mail {
    
    public $settings;
    public $transport;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_mail_db_connection',
            'module_mail_charset',
            'module_mail_x_mailer'
        ]);

        foreach ((array) APP::Module('Registry')->Get(['module_mail_transport'])['module_mail_transport'] as $transport) {
            $transport_settings = json_decode($transport, true);
            $this->transport[$transport_settings[0]] = [
                $transport_settings[1], 
                $transport_settings[2], 
                $transport_settings[3]
            ];
        }
    }
    

    public function Admin() {
        return APP::Render('mail/admin/nav', 'content');
    }
    
    public function Send($transport, $from, $to, $subject, $message, $headers = false) {
        if (!filter_var($from[0], FILTER_VALIDATE_EMAIL)) return ['error', 1];
        if (!$from[1]) return ['error', 2];
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return ['error', 3];
        if (!$subject) return ['error', 4];
        if (!$message[0]) return ['error', 5];
        if (!$message[1]) return ['error', 6];
        if (!array_key_exists($transport, $this->transport)) return ['error', 7];
        
        return APP::Module($this->transport[$transport][0])->{$this->transport[$transport][1]}($from, $to, $subject, $message, $headers);
    }

    
    private function Transport($from, $to, $subject, $message, $headers) {
        $message_from = mb_encode_mimeheader($from[1], $this->settings['module_mail_charset'], "B") . ' <' . $from[0] . '>';
        $message_subject = mb_encode_mimeheader($subject, $this->settings['module_mail_charset'], "B");

        $message_id = sprintf("%s.%s@%s", base_convert(microtime(), 10, 36), base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36), APP::$conf['location'][1]);
        $boundary = md5(uniqid());

        $message_headers = [
            'From: ' . $message_from,
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'Message-ID: <' . $message_id . '>',
            'X-Mailer: ' . $this->settings['module_mail_x_mailer']
        ];
        
        if ($headers) {
            foreach ($headers as $key => $value) {
                if ($value) $message_headers[] = $key . ': ' . $value;
            }
        }

        $html_msg = chunk_split(base64_encode($message[0]));
        $text_msg = chunk_split(base64_encode($message[1]));

        $msg = "--" . $boundary . "\r\n"
            . "Content-transfer-encoding: base64\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Mime-Version: 1.0\r\n"
            . "\r\n"
            . $text_msg
            . "\r\n--" . $boundary . "\r\n"
            . "Content-transfer-encoding: base64\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Mime-Version: 1.0\r\n"
            . "\r\n"
            . $html_msg
            . "\r\n--" . $boundary . "--\r\n";

        $res = mail($to, $message_subject, $msg, implode("\r\n", $message_headers), '-fbounce-' . md5($to) . '@' . APP::$conf['location'][1]) ? ['success', $message_id] : ['error', 0];
    
        APP::Module('Triggers')->Exec('mail_send_letter', [
            'result' => $res,
            'transport' => 'default',
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'headers' => $headers
        ]);
        
        return $res;
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
            ['id', 'sub_id', 'name'], 'letters_groups',
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
            ['id', 'sub_id', 'name'], 'senders_groups',
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
            ['id'], 'letters_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveLettersGroup($value);
        }
        
        APP::Module('DB')->Delete(
            $this->settings['module_mail_db_connection'], 'letters_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }
    
    private function RemoveSendersGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'senders_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveSendersGroup($value);
        }
        
        APP::Module('DB')->Delete(
            $this->settings['module_mail_db_connection'], 'senders_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }
    

    public function ManageLetters() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        $list = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name'], 'letters_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'subject'], 'letters',
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
            ['id', 'name'], 'senders_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'name', 'email'], 'senders',
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
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $letter_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['letter_id_hash']);
        
        APP::Render(
            'mail/admin/letters/preview', 'include', 
            [
                'letter' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['subject', 'html', 'plaintext'], 'letters',
                    [['id', '=', $letter_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderLettersGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function AddLetter() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        APP::Render('mail/admin/letters/add', 'include', [
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
                'letter' => APP::Module('DB')->Select(
                    $this->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['sender_id', 'subject', 'html', 'plaintext', 'list_id'], 'letters',
                    [['id', '=', $letter_id, PDO::PARAM_INT]]
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
                    ['name', 'email'], 'senders',
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
                    ['name'], 'letters_groups',
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
                    ['name'], 'senders_groups',
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
        
        APP::Render('mail/admin/transport/edit', 'include', json_decode(APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['value'], 'registry',
            [['id', '=', $transport_id, PDO::PARAM_INT]]
        ), 1));
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
                ['COUNT(id)'], 'letters_groups',
                [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'senders',
            [['id', '=', $_POST['sender_id'], PDO::PARAM_INT]]
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
        
        if ($out['status'] == 'success') {
            $out['letter_id'] = APP::Module('DB')->Insert(
                $this->settings['module_mail_db_connection'], 'letters',
                Array(
                    'id' => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'sender_id' => [$_POST['sender_id'], PDO::PARAM_INT],
                    'subject' => [$_POST['subject'], PDO::PARAM_STR],
                    'html' => [$_POST['html'], PDO::PARAM_STR],
                    'plaintext' => [$_POST['plaintext'], PDO::PARAM_STR],
                    'list_id' => [$_POST['list_id'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('mail_add_letter', [
                'id' => $out['letter_id'],
                'group_id' => $group_id,
                'sender_id' => $_POST['sender_id'],
                'subject' => $_POST['subject'],
                'html' => $_POST['html'],
                'plaintext' => $_POST['plaintext'],
                'list_id' => $_POST['list_id']
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
                ['COUNT(id)'], 'senders_groups',
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
                $this->settings['module_mail_db_connection'], 'senders',
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
            ['COUNT(id)'], 'letters',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'letters',
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
            ['COUNT(id)'], 'senders',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_mail_db_connection'], 'senders',
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
            ['COUNT(id)'], 'letters',
            [['id', '=', $letter_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'senders',
            [['id', '=', $_POST['sender_id'], PDO::PARAM_INT]]
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
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_mail_db_connection'], 'letters', 
                [
                    'sender_id' => $_POST['sender_id'],
                    'subject' => $_POST['subject'],
                    'html' => $_POST['html'],
                    'plaintext' => $_POST['plaintext'],
                    'list_id' => $_POST['list_id']
                ], 
                [['id', '=', $letter_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('mail_update_letter', [
                'id' => $letter_id,
                'sender_id' => $_POST['sender_id'],
                'subject' => $_POST['subject'],
                'html' => $_POST['html'],
                'plaintext' => $_POST['plaintext'],
                'list_id' => $_POST['list_id']
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
            ['COUNT(id)'], 'senders',
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
                $this->settings['module_mail_db_connection'], 'senders', 
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
                ['COUNT(id)'], 'letters_groups',
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
                $this->settings['module_mail_db_connection'], 'letters_groups',
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
                ['COUNT(id)'], 'senders_groups',
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
                $this->settings['module_mail_db_connection'], 'senders_groups',
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
            ['COUNT(id)'], 'letters_groups',
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
            ['COUNT(id)'], 'senders_groups',
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
            ['COUNT(id)'], 'letters_groups',
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
                $this->settings['module_mail_db_connection'], 'letters_groups', 
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
    
    public function APIUpdateSendersGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_mail_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'senders_groups',
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
                $this->settings['module_mail_db_connection'], 'senders_groups', 
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
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_x_mailer']], [['item', '=', 'module_mail_x_mailer', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_mail_charset']], [['item', '=', 'module_mail_charset', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('mail_update_settings', [
            'db_connection' => $_POST['module_mail_db_connection'],
            'x_mailer' => $_POST['module_mail_x_mailer'],
            'charset' => $_POST['module_mail_charset']
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
        $transports = [];
        $rows = [];
        
        $tmp_transports = APP::Module('Registry')->Get(['module_mail_transport'], ['id', 'value']);
        
        foreach (array_key_exists('module_mail_transport', $tmp_transports) ? (array) $tmp_transports['module_mail_transport'] : [] as $transport) {
            $transport_value = json_decode($transport['value'], 1);

            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', $transport_value[0]) === 0)) continue;
            
            array_push($transports, [
                'id' => $transport['id'],
                'action' => $transport_value[0],
                'module' => $transport_value[1],
                'method' => $transport_value[2],
                'settings' => $transport_value[3],
                'token' => APP::Module('Crypt')->Encode($transport['id'])
            ]);
        }
        
        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($transports[$x])) continue;
            array_push($rows, $transports[$x]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($transports)
        ]);
        exit;
    }
    
    public function APIAddTransport() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['transport'][0])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['transport'][1])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['transport'][2])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['transport'][3])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if ($out['status'] == 'success') {
            $out['transport_id'] = APP::Module('Registry')->Add('module_mail_transport', json_encode($_POST['transport']));
        
            APP::Module('Triggers')->Exec('mail_add_transport', [
                'id' => $out['transport_id'],
                'action' => $_POST['transport'][0],
                'module' => $_POST['transport'][1],
                'method' => $_POST['transport'][2],
                'settings' => $_POST['transport'][3]
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
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'registry',
            [['id', '=', $transport_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['transport'][0])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['transport'][1])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['transport'][2])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['transport'][3])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('Registry')->Update(['value' => json_encode($_POST['transport'])], [['id', '=', $transport_id, PDO::PARAM_INT]]);
            
            APP::Module('Triggers')->Exec('mail_update_transport', [
                'id' => $transport_id,
                'action' => $_POST['transport'][0],
                'module' => $_POST['transport'][1],
                'method' => $_POST['transport'][2],
                'settings' => $_POST['transport'][3]
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
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'registry',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('Registry')->Delete([['id', '=', $_POST['id'], PDO::PARAM_INT]]);
            APP::Module('Triggers')->Exec('mail_remove_transport', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
}