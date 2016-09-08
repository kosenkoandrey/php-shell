<?
class Cache {

    public $settings;
    public $memcache;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_cache_memcache_host', 
            'module_cache_memcache_port'
        ]);
        
        $this->memcache = new Memcached();
        $this->memcache->addServer($this->settings['module_cache_memcache_host'], $this->settings['module_cache_memcache_port']);
    }

    public function Admin() {
        return APP::Render('cache/admin/nav', 'content');
    }

    
    public function Settings() {
        APP::Render('cache/admin/index');
    }

    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_cache_memcache_host']], [['item', '=', 'module_cache_memcache_host', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_cache_memcache_port']], [['item', '=', 'module_cache_memcache_port', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_cache_settings', [
            'memcache_host' => $_POST['module_cache_memcache_host'],
            'memcache_port' => $_POST['module_cache_memcache_port']
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