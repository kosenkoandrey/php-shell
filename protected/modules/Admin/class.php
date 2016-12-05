<?
class Admin {

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Overview() {
        $cards = [];
        
        foreach (APP::$modules as $value) {
            if (method_exists($value, 'Dashboard')) {
                foreach ($value->Dashboard() as $dashboard) {
                    if (count($dashboard[0]) == 1) {
                        $cards[$dashboard[0][0]]['type'] = 'card';
                        $cards[$dashboard[0][0]]['data'] = $dashboard[1];
                    } else {
                        $cards[$dashboard[0][0]]['type'] = 'tab';
                        $cards[$dashboard[0][0]]['data'][$dashboard[0][1]] = $dashboard[1];
                    }
                }
            }
        }
        
        APP::Render('admin/index', 'include', compact('cards'));
    }
    
    public function Application() {
        APP::Render('admin/app');
    }
    
    public function InstalledModules() {
        APP::Render('admin/modules/index');
    }
    
    public function System() {
        $df = null;
        exec('df -h', $df);
        $top = sys_getloadavg();
        $free = null;
        exec('free', $free);
        $df_data = [];
        
        foreach ($df as $v1) {
            $line = [];
            foreach (explode(" ", $v1) as $v2) if ($v2) $line[] = $v2;
            $df_data[] = $line;
        }
        
        $free_data = [];
        
        foreach ($free as $v3) {
            $line = [];
            foreach (explode(" ", $v3) as $v4) if ($v4) $line[] = $v4;
            $free_data[] = $line;
        }

        return [$top, $df_data, $free_data];
    }

    public function ImportModules() {
        if (isset($_FILES['modules'])) {
            foreach ($_FILES['modules']['name'] as $key => $value) {
                if ($value) {
                    APP::Module('Triggers')->Exec('import_locale_module', [
                        'module' => basename($value),
                        'result' => move_uploaded_file($_FILES['modules']['tmp_name'][$key], ROOT . '/protected/import/' . basename($value))
                    ]);
                }
            }
        }
        
        APP::Render('admin/modules/import');
    }
    
    public function NetworkImportModules() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        APP::Import('admin/modules/network_import');
    }
    
    public function RemoveImportedModule() {
        $module = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['module_path']);
        unlink($module);
        APP::Module('Triggers')->Exec('remove_imported_module', ['module' => basename($module)]);
        
        header('Location: ' . APP::Module('Routing')->root . 'admin/modules/import');
        exit;
    }
    
    public function InstallImportedModules() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        APP::Install('admin/modules/install');
    }
    
    public function ExportModule() {
        $module = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['module_hash']);
        APP::Module('Triggers')->Exec('export_module', ['module' => $module]);
        APP::ExportModule($module);
    }
    
    public function UninstallModule() {   
        $module = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['module_hash']);
        APP::Render('admin/modules/uninstall', 'include',[$module, APP::InstalledModuleDependencies($module)]);
    }
    
    public function APIUninstallModule() {   
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        $module = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['module_hash']);
        APP::Module('Triggers')->Exec('uninstall_module', ['module' => $module]);
        
        echo json_encode(APP::UninstallModule($module));
        exit;
    }

}