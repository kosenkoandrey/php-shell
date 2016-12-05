<?
class Triggers {

    public $list = [];
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    function Init() {
        $types = APP::Module('Registry')->Get(['module_trigger_type'], ['id', 'value']);
        
        foreach (array_key_exists('module_trigger_type', $types) ? (array) $types['module_trigger_type'] : [] as $type) {
            $rules = APP::Module('Registry')->Get(['module_trigger_rule'], 'value', $type['id']);
            $type_data = json_decode($type['value'] ,1);
            $trigger_rules = [];
            
            foreach (array_key_exists('module_trigger_rule', $rules) ? (array) $rules['module_trigger_rule'] : [] as $rule) {
                $trigger_rules[] = json_decode($rule ,1);
            }

            $this->list[$type_data[0]] = [
                'id' => $type['id'], 
                'group' => $type_data[1], 
                'name' => $type_data[2], 
                'rules' => $trigger_rules
            ];
        }
    }

    
    public function Register($id, $group, $name) {
        APP::Module('Registry')->Add('module_trigger_type', '["' . $id . '", "' . $group . '", "' . $name . '"]');
    }
    
    public function Unregister($id) {
        if (is_array($id)) {
            foreach ($id as $value) {
                APP::Module('Registry')->Delete([
                    ['item', '=', 'module_trigger_type', PDO::PARAM_STR],
                    ['value', 'LIKE', '[\"' . $value . '\"%', PDO::PARAM_STR]
                ]);
            }
        } else {
            APP::Module('Registry')->Delete([
                ['item', '=', 'module_trigger_type', PDO::PARAM_STR],
                ['value', 'LIKE', '[\"' . $id . '\"%', PDO::PARAM_STR]
            ]);
        }
    }
    
    
    public function Exec($id, $data) {
        foreach (isset($this->list[$id]) ? $this->list[$id]['rules'] : [] as $rule) {
            $data = APP::Module($rule[0])->{$rule[1]}($id, $data);
        }
        
        return $data;
    }
    
    
    public function Admin() {
        return APP::Render('triggers/admin/nav', 'content');
    }
    
    
    public function Manage() {
        APP::Render('triggers/admin/index');
    }
    
    public function Add() {
        $tmp_triggers = APP::Module('Registry')->Get(['module_trigger_type'], ['id', 'value']);
        $trigger_types = [];
        
        foreach (array_key_exists('module_trigger_type', $tmp_triggers) ? (array) $tmp_triggers['module_trigger_type'] : [] as $trigger) {
            $trigger_value = json_decode($trigger['value'], 1);
            $trigger_types[$trigger_value[1]][] = [$trigger['id'], $trigger_value[2]];
        }
        
        APP::Render('triggers/admin/add', 'include', ['types' => $trigger_types]);
    }
    
    public function Edit() {
        $tmp_triggers = APP::Module('Registry')->Get(['module_trigger_type'], ['id', 'value']);
        $trigger_types = [];
        
        foreach (array_key_exists('module_trigger_type', $tmp_triggers) ? (array) $tmp_triggers['module_trigger_type'] : [] as $trigger) {
            $trigger_value = json_decode($trigger['value'], 1);
            $trigger_types[$trigger_value[1]][] = [$trigger['id'], $trigger_value[2]];
        }
        
        $trigger = APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['sub_id', 'value'], 'registry',
            [['id', '=', APP::Module('Crypt')->Decode(APP::Module('Routing')->get['trigger_id_hash']), PDO::PARAM_INT]]
        );
        
        APP::Render('triggers/admin/edit', 'include', [
            'types' => $trigger_types,
            'trigger' => [
                'sub_id' => $trigger['sub_id'],
                'target' => json_decode($trigger['value'], 1)
            ]
        ]);
    }
    
    
    public function APIList() {
        $triggers = [];
        $rows = [];
        
        $tmp_triggers = APP::Module('Registry')->Get(['module_trigger_rule'], ['sub_id', 'id', 'value']);
        
        foreach (array_key_exists('module_trigger_rule', $tmp_triggers) ? (array) $tmp_triggers['module_trigger_rule'] : [] as $trigger) {
            $trigger_value = json_decode($trigger['value'], 1);
            
            $trigger_type = json_decode(APP::Module('DB')->Select(
                APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
                ['value'], 'registry',
                [
                    ['id', '=', $trigger['sub_id'], PDO::PARAM_INT]
                ]
            ), 1);
            
            if (($_POST['searchPhrase']) && (preg_match('/^' . $_POST['searchPhrase'] . '/', $trigger_type[1]) === 0)) continue;
            
            array_push($triggers, [
                'id' => $trigger['id'],
                'sub_id' => $trigger['sub_id'],
                'trigger' => $trigger_type,
                'module' => $trigger_value[0],
                'method' => $trigger_value[1],
                'token' => APP::Module('Crypt')->Encode($trigger['id'])
            ]);
        }
        
        for ($x = ($_POST['current'] - 1) * $_POST['rowCount']; $x < $_POST['rowCount'] * $_POST['current']; $x ++) {
            if (!isset($triggers[$x])) continue;
            array_push($rows, $triggers[$x]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rowCount'],
            'rows' => $rows,
            'total' => count($triggers)
        ]);
        exit;
    }
    
    public function APIAdd() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (empty($_POST['sub_id'])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['target'][0])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['target'][1])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }

        if ($out['status'] == 'success') {
            $out['trigger_id'] = APP::Module('Registry')->Add('module_trigger_rule', json_encode($_POST['target']), $_POST['sub_id']);
            
            $this->Exec('add_trigger', [
                'id' => $out['trigger_id'],
                'module' => $_POST['target'][0],
                'method' => $_POST['target'][1],
                'sub_id' => $_POST['sub_id']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdate() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $trigger_id = APP::Module('Crypt')->Decode($_POST['trigger']);

        if (!APP::Module('DB')->Select(
            APP::Module('Registry')->conf['connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'registry',
            [['id', '=', $trigger_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if (empty($_POST['sub_id'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['target'][0])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['target'][1])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('Registry')->Update([
                'sub_id' => $_POST['sub_id'],
                'value' => json_encode($_POST['target'])
            ], [['id', '=', $trigger_id, PDO::PARAM_INT]]);
            
            $this->Exec('update_trigger', [
                'id' => $trigger_id,
                'module' => $_POST['target'][0],
                'method' => $_POST['target'][1],
                'sub_id' => $_POST['sub_id']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }

    public function APIRemove() {
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
            $this->Exec('remove_trigger', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
}