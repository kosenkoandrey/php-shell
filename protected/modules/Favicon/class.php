<?
class Favicon {

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function OutMain() {
        header('Content-Type: image/x-icon');
        echo file_get_contents(ROOT . '/protected/render/favicon/favicon.ico');
    }
    
    public function OutBrand() {
        $file = ROOT . '/protected/render/favicon/' . APP::Module('Routing')->get['brand'] . '-icon-' . APP::Module('Routing')->get['size'] . '.png';
        
        if (file_exists($file)) {
            header('Content-Type: image/png');
            echo file_get_contents($file);
        } else {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
    }
    
    public function OutApple() {
        header('Content-Type: image/png');
        echo file_get_contents(ROOT . '/protected/render/favicon/apple-icon.png');
    }
    
    public function OutFavicon() {
        $file = ROOT . '/protected/render/favicon/favicon-' . APP::Module('Routing')->get['size'] . '.png';
        
        if (file_exists($file)) {
            header('Content-Type: image/png');
            echo file_get_contents($file);
        } else {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
    }
    
    public function OutManifest() {
        header('Content-Type: application/json');
        echo file_get_contents(ROOT . '/protected/render/favicon/manifest.json');
    }
    
    public function OutBrowserconfig() {
        header('Content-Type: text/xml');
        echo file_get_contents(ROOT . '/protected/render/favicon/browserconfig.xml');
    }

}