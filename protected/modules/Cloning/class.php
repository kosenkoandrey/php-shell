<?
class Cloning {
    
    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_cloning_db_connection'
        ]);
    }
    
    public function Admin() {
        return APP::Render('cloning/admin/nav', 'content');
    }

    
    public function NewCloning() {
        APP::Render('cloning/admin/index');
    }
    
    public function Log() {
        APP::Render('cloning/admin/log');
    }
    
   
    
    public function APICloning() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if ($out['status'] == 'success') {
            $dist = $this->conf['profiles'][$_POST['profile']]['path'];
            
            exec('rm -R ' . $dist);
            exec('mkdir -m 755 ' . $dist);

            require_once ROOT . '/protected/modules/Cloning/profiles/' . $_POST['profile'] . '/index.php';

            $out['cloning_id'] = APP::Module('DB')->Insert(
                $this->settings['module_cloning_db_connection'], 'cloning_log',
                [
                    'id' => 'NULL',
                    'profile' => [$_POST['profile'], PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                ]
            );

            APP::Module('Triggers')->Exec('exec_cloning', [
                'id' => $out['cloning_id'],
                'profile' => $_POST['profile']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
}
