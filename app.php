<?
class APP {

    public static $conf = [];
    public static $modules = [];
    public static $handlers = [];

    public static function Init($conf, $argv) {
        define('RUNTIME', microtime(true));
        
        ini_set('memory_limit', $conf['memory_limit']);
        setlocale(LC_ALL, $conf['locale'] . '.' . $conf['encoding']);
        date_default_timezone_set($conf['timezone']);
        error_reporting($conf['error_reporting']);
        
        set_error_handler('APP::ErrorHandler', E_ALL & ~E_NOTICE);
        set_exception_handler('APP::ExceptionHandler');
        register_shutdown_function('APP::ShutdownHandler');

        self::$conf = $conf;
        
        if (!file_exists($conf['logs'])) {
            mkdir($conf['logs']);
        }
        
        if (($conf['install']) && (!count($argv))) {
            session_start();
            
            switch ($_SERVER['REQUEST_URI']) {
                case APP::$conf['location'][2] . 'install': self::Install('core/install'); break;
                case APP::$conf['location'][2] . 'import': self::Import('core/import'); break;
            }

            exit;
        }

        foreach (glob(ROOT . '/protected/modules/*') as $path) self::LoadModule($path);
        foreach (self::$modules as $module) self::InitModule($module);

        if (count($argv)) {
            $args = [];
            
            for ($x = array_search('init.php', (array) $argv) + 1; $x < count($argv); $x ++) {
                $args[] = $argv[$x];
            }
            
            switch ($args[0]) {
                case 'test':
                    foreach (glob(ROOT . '/protected/modules/*') as $path) self::LoadModuleTest($path);
                    
                    $module = $args[1];
                    $method = $args[2];
                    
                    unset($args[0], $args[1], $args[2]);
                    break;
                default:
                    $module = $args[0];
                    $method = $args[1];
                    
                    unset($args[0], $args[1]);
                    break;
            }
            
            call_user_func_array([self::Module($module), $method], $args);
            exit;
        } else {
            foreach (self::$handlers as $handler) self::Module($handler[0])->{$handler[1]}($handler[2]);
        }
    }
    
    
    public static function Install($tpl) {
        foreach (glob(ROOT . '/protected/import/*.zip') as $module) {
            if ($result = self::InstallModule(basename($module, '.zip'))) {
                self::Render($tpl, 'include', $result); 
                exit;
            }
        }

        unset($_SESSION['core']['install']);
        self::Render($tpl);
    }
    
    public static function Import($tpl) {
        if (isset($_POST['action'])) {
            $installed_modules = [];
            foreach (glob(ROOT . '/protected/modules/*') as $module) $installed_modules[] = basename($module);
                    
            switch ($_POST['action']) {
                case 'set_server':
                    $_SESSION['core']['import']['server'] = $_POST['server'];
                    self::Render($tpl, 'include', ['action' => 'select_modules', 'installed_modules' => $installed_modules]);
                    break;
                case 'reset_server':
                    unset($_SESSION['core']['import']['server']);
                    self::Render($tpl, 'include', ['action' => 'set_server']);
                    break;
                case 'select_modules':
                    $_SESSION['core']['import']['modules'] = $_POST['modules'];
                    self::Render($tpl, 'include', ['action' => 'selected_modules',]);
                    break;
                case 'reset_modules':
                    unset($_SESSION['core']['import']['modules']);
                    self::Render($tpl, 'include', ['action' => 'select_modules', 'installed_modules' => $installed_modules]);
                    break;
                case 'import_modules':
                    foreach (explode(' ', $_SESSION['core']['import']['modules']) as $module) {
                        file_put_contents(
                            ROOT . '/protected/import/' . $module . '.zip', 
                            file_get_contents($_SESSION['core']['import']['server'] . '/public/export/modules/' . $module . '.zip')
                        );
                    }
                    
                    self::Render($tpl, 'include', ['action' => 'import_modules']);
                    unset($_SESSION['core']['import']);
                    break;
            }
        } else {
            self::Render($tpl, 'include', ['action' => 'set_server']);
        }

        exit;
    }

    
    public static function ErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        self::Error('core/error', 0, [$errno, $errstr, $errfile, $errline, $errcontext, debug_backtrace()]);
    }

    public static function ExceptionHandler($error) {
        self::Error('core/error', 1, [$error->getMessage(), $error->getCode(), $error->getFile(), $error->getLine(), $error->getTrace()]);
    }
    
    public static function ShutdownHandler() {
        $error = error_get_last();
        if ($error) self::Error('core/error', 2, $error);
    }

    
    public static function InstallModule($module) {
        if (is_dir(ROOT . '/protected/modules/' . $module)) {
            return false;
        }
        
        $archive = new ZipArchive;
        
        if ($archive->open(ROOT . '/protected/import/' . $module . '.zip')) {
            $manifest = $archive->getFromName('protected/modules/' . $module . '/MANIFEST');
            
            if (!$manifest) {
                return 'Empty manifest file [' . $module . ']';
            }

            preg_match('/\[dependencies\](.*)\[\/dependencies\]/ismU', $manifest, $manifest_dependencies);
            
            if (array_key_exists(1, $manifest_dependencies)) {
                foreach (explode(' ', $manifest_dependencies[1]) as $parent_module) {
                    if (!is_dir(ROOT . '/protected/modules/' . $parent_module)) {
                        if (file_exists(ROOT . '/protected/import/' . $parent_module . '.zip')) {
                            if ($parent_module_result = self::InstallModule($parent_module)) {
                                return $parent_module_result;
                            }
                        } else {
                            return 'Unsatisfied dependencies [' . $parent_module . ']';
                        }
                    }
                }
            }
            
            echo self::Render($archive->getFromName('protected/modules/' . $module . '/install.php'), 'eval', $archive);
            $archive->close();
        } else {
            return 'Unable to open [' . $module . '] archive';
        }

        return false;
    }
    
    public static function InstalledModuleDependencies($module) {
        $dependencies = [];
        
        foreach (glob(ROOT . '/protected/modules/*') as $installed_module) {
            $manifest = file_get_contents(ROOT . '/protected/modules/' . basename($installed_module) . '/MANIFEST');
            preg_match('/\[dependencies\](.*)\[\/dependencies\]/ismU', $manifest, $manifest_dependencies);
            
            if (isset($manifest_dependencies[1])) {
                if (array_search($module, explode(' ', $manifest_dependencies[1])) !== false) {
                    $dependencies[] = basename($installed_module);
                }
            }
        }
        
        if (count($dependencies)) {
            foreach ($dependencies as $value) {
                $dependencies = array_merge($dependencies, self::InstalledModuleDependencies($value));
            }
        }
        
        return array_values(array_unique($dependencies));
    }
    
    public static function UninstallModule($module) {
        if (!is_dir(ROOT . '/protected/modules/' . $module)) {
            return false;
        }
        
        $modules = array_merge([$module], self::InstalledModuleDependencies($module));
        
        foreach ($modules as $installed_module) {
            self::Render(file_get_contents(ROOT . '/protected/modules/' . $installed_module . '/uninstall.php'), 'eval');
            
            $manifest = file_get_contents(ROOT . '/protected/modules/' . $installed_module . '/MANIFEST');
            preg_match_all('/\[file\](.*)\[\/file\]/ismU', $manifest, $manifest_files);
            
            $module_dirs = [];
            
            foreach ($manifest_files[1] as $file) {
                $module_dirs[] = ROOT . '/' . dirname($file);
                if (is_file($file)) unlink(ROOT . '/' . $file);
            }
            
            $out_module_path = [];

            foreach (array_values(array_reverse(array_unique($module_dirs))) as $module_dir) {
                if (is_dir($module_dir)) {
                    $path = explode('/', $module_dir);
                    
                    $module_path = [];
                    $target_module_path = [];
                    
                    $path_found = false;
                    $path_type = false;
                    
                    foreach ($path as $key => $value) {
                        if ($path_found) {
                            $module_path[] = $value;
                            $target_module_path[] = ROOT . '/protected/' . $path_type . '/' . implode('/', $module_path);
                        }
                        
                        if (($value == 'render') || ($value == 'modules')) {
                            $path_found = true;
                            $path_type = $value;
                        }
                    }
                    
                    foreach (array_reverse($target_module_path) as $value) {
                        if (is_dir($value)) {
                            $out_module_path[$value] = count(explode('/', $value));
                        }
                    }
                }
            }
            
            arsort($out_module_path);
            foreach ($out_module_path as $key => $value) rmdir($key);
        }

        return $modules;
    }
    
    public static function ExportModule($module) {
        $manifest = file_get_contents(ROOT . '/protected/modules/' . $module . '/MANIFEST');

        $tmp_file = tempnam('tmp', 'zip');
        $archive = new ZipArchive;
        
        $archive->open($tmp_file, ZIPARCHIVE::OVERWRITE);
        preg_match_all('/\[file\](.*)\[\/file\]/ismU', $manifest, $manifest_file);
        foreach ($manifest_file[1] as $file) $archive->addFile($file);
        $archive->close();

        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($tmp_file));
        header('Content-Disposition: attachment; filename="' . $module . '.zip"');

        readfile($tmp_file);
        unlink($tmp_file);
        exit;
    }
    
    public static function LoadModule($path) {
        $name = basename($path);
        $class = $path . '/class.php';
        $conf = $path . '/conf.php';

        if (!file_exists($class)) {
            self::Error('core/error', 3, $name);
        }

        if (!array_key_exists($name, self::$modules)) {
            include_once $class;

            if (!class_exists($name)) {
                self::Error('core/error', 4, $name);
            }

            $conf_data = array_replace_recursive(['init' => false], file_exists($conf) ? require_once $conf : []);
            
            self::$modules[$name] = new $name($conf_data);
            self::$modules[$name]->conf = $conf_data;
        }
        
        return self::$modules[$name];
    }
    
    public static function LoadModuleTest($path) {
        $name = basename($path) . 'Test';
        $class = $path . '/test.php';

        if (!file_exists($class)) {
            self::Error('core/error', 6, $name);
        }
        
        if (!array_key_exists($name, self::$modules)) {
            include_once $class;

            if (!class_exists($name)) {
                self::Error('core/error', 7, $name);
            }

            self::$modules[$name] = new $name();
            self::$modules[$name]->conf = ['init' => false];
        }

        return self::$modules[$name];
    }
    
    public static function InitModule($module, $force = false) {
        $return = false;
        
        if (($module->conf['init']) || ($force)) {
            if (method_exists($module, 'Init')) {
                if (!is_null($module->conf['init'])) {
                    $module->conf['init'] = null;
                    $return = $module->Init();
                }
            }
        }

        return $return;
    }
    
    public static function ModuleDependencies($module) {
        $dependencies = [];
        
        $manifest = file_get_contents(ROOT . '/protected/modules/' . $module . '/MANIFEST');
        preg_match('/\[dependencies\](.*)\[\/dependencies\]/ismU', $manifest, $manifest_dependencies);

        if (isset($manifest_dependencies[1])) {
            $module_dependencies = explode(' ', $manifest_dependencies[1]);
            $dependencies = array_merge($dependencies, $module_dependencies);
            foreach ($module_dependencies as $dep_module) $dependencies = array_merge($dependencies, self::ModuleDependencies($dep_module));
        }

        return array_values(array_unique($dependencies));
    }
    
    public static function Module($module) {
        if (!array_key_exists($module, self::$modules)) self::LoadModule(ROOT . '/protected/modules/' . $module);
        self::InitModule(self::$modules[$module], true);
        
        return self::$modules[$module];
    }
    
    
    public static function Render($src, $mode = 'include', $data = null, $ext = '.php') {
        if ($mode != 'eval') {
            $file = ROOT . '/protected/render/' . $src . '.php';
            if (!file_exists($file)) self::Error('core/error', 5, $src);
        }

        switch ($mode) {
            case 'include': include $file; break;
            case 'return': return include $file;
            case 'content':
                ob_start();
                include $file;
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
            case 'eval':
                ob_start();
                eval('?>' . $src) . '<?';
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
        }
    }
    
    public static function Error($view, $code, $details = null) {
        if (self::$conf['logs']) {
            file_put_contents(
                self::$conf['logs'] . '/php-errors-' . date('d-m-Y', time()) . '.log',
                json_encode([date('H:i:s', time()), $code, $details]) . "\n",
                FILE_APPEND
            );
        }
        
        self::Render($view, 'include', [$code, $details]);
        die();
    }
    
}