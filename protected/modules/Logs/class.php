<?
class Logs {

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Admin() {
        return APP::Render('logs/admin/nav', 'content');
    }
    

    public function Manage() {
        APP::Render('logs/admin/index');
    }
    
    public function View() {
        $file = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['filename_hash']);
        APP::Render('logs/admin/view', 'include', [$file, file($file)]);
    }
    
    
    public function APIListLogs() {
        $logs = [];
        $rows = [];
        
        foreach (glob(APP::$conf['logs'] . '/*.log') as $log) {
            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', basename($log)) === 0)) continue;
            
            array_push($logs, [
                'filename' => basename($log),
                'size' => APP::Module('Utils')->SizeConvert(filesize($log)),
                'token' => APP::Module('Crypt')->Encode($log)
            ]);
        }
        
        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($logs[$x])) continue;
            array_push($rows, $logs[$x]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($logs)
        ]);
        exit;
    }
    
    public function APIRemoveLog() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if ($out['status'] == 'success') {
            $file = APP::Module('Crypt')->Decode($_POST['token']);
            $out['count'] = [$file, file_exists($file) ? unlink($file) : false];
            
            APP::Module('Triggers')->Exec('remove_log_file', [
                'file' => $file,
                'result' => $out['count']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

}