<?
class Crypt {

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Admin() {
        return APP::Render('crypt/admin/nav', 'content');
    }
    
    
    public function SafeB64Encode($string) {
        $data = base64_encode($string);
        
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }

    public function SafeB64Decode($string) {
        $data = str_replace(['-', '_', ''], ['+', '/', '='], $string);
        $mod4 = strlen($data) % 4;
        
        if ($mod4) $data .= substr('====', $mod4);

        return base64_decode($data);
    }

    public function Encode($value) {
        if (!$value) return false;

        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, APP::Module('Registry')->Get('module_crypt_key'), $value, MCRYPT_MODE_ECB, $iv);

        return trim($this->SafeB64Encode($crypttext));
    }

    public function Decode($value) {
        if (!$value) return false;

        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, APP::Module('Registry')->Get('module_crypt_key'), $this->SafeB64Decode($value), MCRYPT_MODE_ECB, $iv);

        return trim($decrypttext);
    }

    
    public function Settings() {
        APP::Render('crypt/admin/index', 'include', APP::Module('Registry')->Get('module_crypt_key'));
    }
    
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_crypt_key']], [['item', '=', 'module_crypt_key', PDO::PARAM_STR]]);

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