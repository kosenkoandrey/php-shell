<?
class Sessions {

    public $session;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        ini_set('session.gc_maxlifetime', APP::Module('Registry')->Get('module_sessions_gc_maxlifetime'));
        ini_set('session.cookie_lifetime', APP::Module('Registry')->Get('module_sessions_cookie_lifetime'));
        ini_set('session.cookie_domain', APP::Module('Registry')->Get('module_sessions_cookie_domain'));

        session_set_save_handler(
            [$this, 'Open'],
            [$this, 'Close'],
            [$this, 'Read'],
            [$this, 'Write'],
            [$this, 'Destroy'],
            [$this, 'Gc']
        );

        register_shutdown_function('session_write_close');
        
        if (isset(APP::Module('Routing')->get['session'])) {
            session_id(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['session']));
        }
        
        session_start();
        $this->session = &$_SESSION;
    }

    public function Admin() {
        return APP::Render('sessions/admin/nav', 'content');
    }
    
    
    public function Open() {
        return true;
    }

    public function Close() {
        return true;
    }

    public function Read($id) {
        $sql = APP::Module('DB')->Open($this->conf['connection'])->prepare('SELECT * FROM sessions WHERE id = :id');
        $sql->bindParam(':id', $id, PDO::PARAM_STR);
        $sql->execute();
        
        $session = $sql->fetch(PDO::FETCH_ASSOC);
        
        if (!is_array($session)) return '';
        return APP::Module('Registry')->Get('module_sessions_compress') ? gzuncompress($session['data']) : $session['data'];
    }

    public function Write($id, $data) {
        $data = APP::Module('Registry')->Get('module_sessions_compress') ? gzcompress($data, APP::Module('Registry')->Get('module_sessions_compress')) : $data;
        
        $sql = APP::Module('DB')->Open($this->conf['connection'])->prepare('REPLACE INTO sessions (id, data) VALUES (:id, :data)');
        $sql->bindParam(':id', $id, PDO::PARAM_STR);
        $sql->bindParam(':data', $data, PDO::PARAM_STR);
        $sql->execute();

        return true;
    }

    public function Destroy($id) {
        $sql = APP::Module('DB')->Open($this->conf['connection'])->prepare('DELETE FROM sessions WHERE id = :id');
        $sql->bindParam(':id', $id, PDO::PARAM_STR);
        $sql->execute();
        
        return true;
    }

    public function Gc($lifetime) {
        $touched = date('Y-m-d H:i:s', time() - $lifetime);
        
        $sql = APP::Module('DB')->Open($this->conf['connection'])->prepare('DELETE FROM sessions WHERE touched < :touched');
        $sql->bindParam(':touched', $touched, PDO::PARAM_STR);
        $sql->execute();

        return true;
    }

    public function Serialize($data, $safe = true) {
        if ($safe) $data = unserialize(serialize($data));

        $raw = '';
        $line = 0;
        $keys = array_keys($data);

        foreach ($keys as $key) {
            $value = $data[$key];
            $line++;
            $raw .= $key . '|';

            if (is_array($value) && isset($value['huge_recursion_blocker_we_hope'])) {
                $raw .= 'R:' . $value['huge_recursion_blocker_we_hope'] . ';';
            } else {
                $raw .= serialize($value);
            }

            $data[$key] = array('huge_recursion_blocker_we_hope' => $line);
        }

        return $raw;
    }

    public function Unserialize($data) {
        if (strlen($data) == 0) return array();
        preg_match_all('/(^|;|\})([a-zA-Z0-9_]+)\|/i', $data, $matchesarray, PREG_OFFSET_CAPTURE);

        $returnArray = [];
        $lastOffset = null;
        $currentKey = '';

        foreach ($matchesarray[2] as $value) {
            $offset = $value[1];

            if (!is_null($lastOffset)) {
                $valueText = substr($data, $lastOffset, $offset - $lastOffset);
                $returnArray[$currentKey] = unserialize($valueText);
            }

            $currentKey = $value[0];
            $lastOffset = $offset + strlen($currentKey) + 1;
        }

        $valueText = substr($data, $lastOffset);
        $returnArray[$currentKey] = unserialize($valueText);

        return $returnArray;
    }

    
    public function Settings() {
        APP::Render(
            'sessions/admin/index', 
            'include', 
            APP::Module('Registry')->Get([
                'module_sessions_cookie_domain',
                'module_sessions_gc_maxlifetime',
                'module_sessions_cookie_lifetime',
                'module_sessions_compress'
            ])
        );
    }
    
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_sessions_cookie_domain']], [['item', '=', 'module_sessions_cookie_domain', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_sessions_gc_maxlifetime']], [['item', '=', 'module_sessions_gc_maxlifetime', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_sessions_cookie_lifetime']], [['item', '=', 'module_sessions_cookie_lifetime', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_sessions_compress']], [['item', '=', 'module_sessions_compress', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_sessions_settings', [
            'cookie_domain' => $_POST['module_sessions_cookie_domain'],
            'gc_maxlifetime' => $_POST['module_sessions_gc_maxlifetime'],
            'cookie_lifetime' => $_POST['module_sessions_cookie_lifetime'],
            'compress' => $_POST['module_sessions_compress']
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