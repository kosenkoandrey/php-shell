<?
class Routing {

    public $root = [];
    public $request = [];
    public $get = [];
    
    public function Init() {
        $this->root = APP::$conf['location'][0] . '://' . APP::$conf['location'][1] . APP::$conf['location'][2];
        $this->request = parse_url($this->SelfUrl());
        if (isset($this->request['query'])) parse_str($this->request['query'], $this->get);
        
        foreach ((array) $this->conf['routes'] as $route) {
            if (preg_match('/^' . preg_quote(APP::$conf['location'][2], '/') . $route[0] . '$/', $this->RequestURI(), $result)) {
                foreach ($result as $index => $value) {
                    if ((!is_numeric($index)) && ($value != '')) {
                        $this->get[$index] = $value;
                    }
                }

                APP::$handlers['route'] = [$route[1], $route[2], isset($route[3]) ? $route[3] : null];
                return;
            }
        }
        
        APP::$handlers['route'] = ['Routing', 'Error', null];
    }

    public function SelfUrl() {
        if (!isset($_SERVER['SERVER_NAME'])) return false;

        $s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
        $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
        $pr = substr($sp, 0, strpos($sp, '/')) . $s;
        $pt = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':' . $_SERVER['SERVER_PORT']);
        
        return $pr . '://' . $_SERVER['SERVER_NAME'] . $pt . $this->RequestURI();
    }
    
    public function RequestURI() {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
    }
    
    public function Add($rule, $module, $method, $data = null) {
        $this->conf['routes'][] = [$rule, $module, $method, $data];
        $this->Init();
    }
    
    public function Error() {
        APP::Render('routing/errors', 'include', 'route_not_found');
    }
    
}