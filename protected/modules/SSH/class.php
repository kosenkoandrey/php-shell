<?
class SSH {

    private $con;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    
    public function Init() {
        $con = APP::Module('Registry')->Get(['module_ssh_connection'], ['id', 'value']);
        if (isset($con['module_ssh_connection'])) foreach ((array) $con['module_ssh_connection'] as $con) $this->Connect($con['id'], json_decode($con['value'], 1));
    }
    
    private function Connect($id, $con) {
        $this->con[$id] = ssh2_connect($con[0], $con[1]);
        ssh2_auth_password($this->con[$id], $con[2], APP::Module('Crypt')->Decode($con[3]));
    }

    public function Open($id) {
        return $this->con[$id];
    }
    
    public function Exec($con, $cmd) {
        return ssh2_exec($this->con[$con], $cmd);
    }
    
    
    public function Admin() {
        return APP::Render('ssh/admin/nav', 'content');
    }
    
    
    public function ManageConnections() {
        APP::Render('ssh/admin/index');
    }
    
    public function AddConnection() {
        APP::Render('ssh/admin/add');
    }
    
    public function EditConnection() {
        $connection_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['connection_id_hash']);
        
        APP::Render('ssh/admin/edit', 'include', json_decode(APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['value'], 'registry',
            [['id', '=', $connection_id, PDO::PARAM_INT]]
        ), 1));
    }
    
    
    public function APIListConnections() {
        $connections = [];
        $rows = [];
        
        $tmp_connections = APP::Module('Registry')->Get(['module_ssh_connection'], ['id', 'value']);
        
        foreach (array_key_exists('module_ssh_connection', $tmp_connections) ? (array) $tmp_connections['module_ssh_connection'] : [] as $connection) {
            $connection_value = json_decode($connection['value'], 1);
            $connection_value_str = $connection_value[2] . '@' . $connection_value[0] . ':' . $connection_value[1];
            
            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', $connection_value_str) === 0)) continue;
            
            array_push($connections, [
                'id' => $connection['id'],
                'value' => $connection_value_str,
                'token' => APP::Module('Crypt')->Encode($connection['id'])
            ]);
        }
        
        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($connections[$x])) continue;
            array_push($rows, $connections[$x]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($connections)
        ]);
        exit;
    }
    
    public function APIAddConnection() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['host'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['port'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['user'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        /*
        if (@ssh2_connect($_POST['host'], $_POST['port']) === false) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
         */
        
        if ($out['status'] == 'success') {
            $out['connection_id'] = APP::Module('Registry')->Add('module_ssh_connection', json_encode([$_POST['host'], $_POST['port'], $_POST['user'], APP::Module('Crypt')->Encode($_POST['password'])]));
        
            APP::Module('Triggers')->Exec('add_ssh_connection', [
                'id' => $out['connection_id'],
                'host' => $_POST['host'],
                'port' => $_POST['port'],
                'user' => $_POST['user'],
                'password' => $_POST['password']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateConnection() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $connection_id = APP::Module('Crypt')->Decode($_POST['connection']);

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'registry',
            [['id', '=', $connection_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['host'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['port'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['user'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['password'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('Registry')->Update(['value' => json_encode([$_POST['host'], $_POST['port'], $_POST['user'], APP::Module('Crypt')->Encode($_POST['password'])])], [['id', '=', $connection_id, PDO::PARAM_INT]]);
        
            APP::Module('Triggers')->Exec('update_ssh_connection', [
                'id' => $connection_id,
                'host' => $_POST['host'],
                'port' => $_POST['port'],
                'user' => $_POST['user'],
                'password' => $_POST['password']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemoveConnection() {
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
            APP::Module('Triggers')->Exec('remove_ssh_connection', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
}