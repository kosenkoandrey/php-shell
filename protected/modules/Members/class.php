<?
class Members {

    public $settings;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_members_db_connection'
        ]);
    }

    public function Admin() {
        return APP::Render('members/admin/nav', 'content');
    }

    private function RenderPagesGroupsPath($group) {
        return $this->GetPagesGroupsPath(0, $this->RenderPagesGroups(), $group);
    }

    private function GetPagesGroupsPath($group, $data, $target, $path = Array()) {
        $path[$group] = $data['name'];

        if ($group == $target) {
            return $path;
        }

        if (count($data['groups'])) {
            foreach ($data['groups'] as $key => $value) {
                $res = $this->GetPagesGroupsPath($key, $value, $target, $path);

                if ($res) {
                    return $res;
                }
            }
        }

        unset($path[$group]);
        return false;
    }

    private function RenderPagesGroups() {
        $out = [
            0 => [
                'name' => '/',
                'groups' => []
            ]
        ];

        $page_groups = APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'sub_id', 'name'], 'members_pages_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        );

        foreach ($page_groups as $group) {
            $out[$group['id']] = [
                'name' => $group['name'],
                'groups' => []
            ];
        }

        foreach ($page_groups as $group) {
            $out[$group['sub_id']]['groups'][$group['id']] = $group['name'];
        }

        return $this->GetPagesGroups($out, $out[0]);
    }
    
    public function GetMemberAccess($user_id){
        $pages = [];
        $tree = [];
        
        $member_access = APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['item_id', 'item'], 'members_access',
            [['user_id', '=', $user_id, PDO::PARAM_INT]]
        );
        
        foreach(APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'group_id', 'title'], 'members_pages',
            [['id', '!=', 0, PDO::PARAM_INT]]
        )as $page){
            $pages[$page['id']] = [
                'type' => 'page',
                'id' => $page['id'],
                'title' => $page['title']
            ];
            
        }
        
        foreach ($member_access as $item) {
            $tree[] = $this->GetTreeByGroup($item['item'], $item['item_id']);
        }

        return $tree;
    }
    
    public function GetTreeByGroup($type, $item_id){
        $out = [];
        $list = [];
        $pages = [];
        $page_id = 0;
        
        switch ($type) {
            case 'g':
                
                $group_id = $item_id;
                
                foreach(APP::Module('DB')->Select(
                    $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
                    ['id', 'group_id', 'title'], 'members_pages',
                    [['id', '=', 0, PDO::PARAM_INT]]
                ) as $page){
                    $pages[$page['group_id']][] = [
                        'type' => 'page',
                        'id' => $page['id'],
                        'title' => $page['title']
                    ];
                }
                
                break;
            case 'p':
                
                $page = APP::Module('DB')->Select(
                    $this->settings['module_members_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['id', 'group_id', 'title'], 'members_pages',
                    [['id', '=', $item_id, PDO::PARAM_INT]]
                );
                $pages[$page['group_id']] = $page;
                $group_id = $page['group_id'];
                        
                break;
        }
        
        foreach(APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'group_id', 'title'], 'members_pages',
            [['id', '=', 0, PDO::PARAM_INT]]
        ) as $page){
            $pages[$page['group_id']][] = [
                'type' => 'page',
                'id' => $page['id'],
                'title' => $page['title']
            ];
        }
        
        $groups = APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'sub_id', 'name'], 'members_pages_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        );
        
        foreach ($groups as $group) {
            if($group['sub_id']){
                $list[$group['sub_id']][$group['id']] = [
                    'type' => 'group',
                    'id'   => $group['id'],
                    'title' => $group['name'],
                    'items' => [],
                    'sub_id' => $group['sub_id']
                ];
            }
            
            $sub_group[$group['id']] = $group['sub_id'];
        }
                 
        foreach ($groups as $item) {
            if(!$item['sub_id']){
                $out[$item['id']] = [
                    'title' => $item['name'],
                    'type' => 'group',
                    'id' => $item['id'],
                    'sub_id' => $item['sub_id']
                ];
                
                if($item['id'] == $group_id){
                    $out[$item['id']]['items'] = array_merge($this->BuildSubGroupTree($item['id'], $list, $group_id, $pages, $group_id == $item['id'] ? true : false), isset($pages[$item['id']]) ? $pages[$item['id']] : []);
                }else{
                    $out[$item['id']]['items'] = $this->BuildSubGroupTree($item['id'], $list, $group_id, $pages);
                }
            }
        }
    
        $id = $this->GetMainParentGroup($sub_group, $group_id);
        return $out[$id];
    }
    
    private function BuildSubGroupTree($id, $list, $group_id = false, $pages = [], $access = false){
        $out = [];

        if(isset($list[$id])){
            foreach ($list[$id] as $item) {
                if($access || ($group_id && $group_id == $item['id'])){
                    $out[] = [
                        'title' => $item['title'],
                        'type' => 'group',
                        'id' => $item['id'],
                        'sub_id' => $item['sub_id'],
                        'items' => array_merge($this->BuildSubGroupTree($item['id'], $list, $group_id, $pages, true), isset($pages[$item['id']]) ? $pages[$item['id']] : [])
                    ];
                }else{
                    $out[] = [
                        'title' => $item['title'],
                        'type' => 'group',
                        'id' => $item['id'],
                        'sub_id' => $item['sub_id'],
                        'items' =>  $this->BuildSubGroupTree($item['id'], $list, $group_id, $pages, $access)
                    ];
                }
            }
        }
        return $out;
    }
    
    private function GetMainParentGroup($list, $id) {
        if($list[$id]){
            return $this->GetMainParentGroup($list, $list[$id]);
        }else{
            return $id;
        }
    }

    private function GetPagesGroups($groups, $data) {
        if (count($data['groups'])) {
            foreach ($data['groups'] as $id => $name) {
                $data['groups'][$id] = $this->GetPagesGroups($groups, $groups[$id]);
            }
        }

        return $data;
    }

    private function RemovePagesGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_COLUMN],
            ['id'], 'members_pages_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemovePagesGroup($value);
        }

        APP::Module('DB')->Delete(
            $this->settings['module_members_db_connection'], 'members_pages_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }

    public function ManagePages() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        $list = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'name'], 'members_pages_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'title'], 'members_pages',
            [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['page', $value['id'], $value['title']];
        }

        APP::Render('members/admin/page/index', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderPagesGroupsPath($group_sub_id),
            'list' => $list
        ]);
    }

    public function PreviewPage() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $page_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['page_id_hash']);

        APP::Render(
            'members/admin/page/preview', 'include',
            [
                'page' => APP::Module('DB')->Select(
                    $this->settings['module_members_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['title', 'content'], 'members_pages',
                    [['id', '=', $page_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderPagesGroupsPath($group_sub_id)
            ]
        );
    }

    public function AddPage() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('members/admin/page/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderPagesGroupsPath($group_sub_id)
        ]);
    }

    public function EditPage() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $page_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['page_id_hash']);

        APP::Render(
            'members/admin/page/edit', 'include',
            [
                'page' => APP::Module('DB')->Select(
                    $this->settings['module_members_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['title', 'content'], 'members_pages',
                    [['id', '=', $page_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderPagesGroupsPath($group_sub_id)
            ]
        );
    }

    public function AddPagesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('members/admin/page/groups/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderPagesGroupsPath($group_sub_id)
        ]);
    }

    public function EditPagesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $group_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);

        APP::Render(
            'members/admin/page/groups/edit', 'include',
            [
                'group' => APP::Module('DB')->Select(
                    $this->settings['module_members_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['name'], 'members_pages_groups',
                    [['id', '=', $group_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderPagesGroupsPath($group_sub_id)
            ]
        );
    }

    public function Settings() {
        APP::Render('members/admin/settings');
    }

    public function APIAddPage() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $group_id = $_POST['group_id'] ? APP::Module('Crypt')->Decode($_POST['group_id']) : 0;

        if ($group_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_members_db_connection'], ['fetchColumn', 0],
                ['COUNT(id)'], 'members_pages_groups',
                [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }

        if (empty($_POST['content'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if ($out['status'] == 'success') {
            $out['page_id'] = APP::Module('DB')->Insert(
                $this->settings['module_members_db_connection'], 'members_pages',
                Array(
                    'id' => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'title' => [$_POST['title'], PDO::PARAM_INT],
                    'content' => [$_POST['content'], PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                )
            );

            APP::Module('Triggers')->Exec('members_add_page', [
                'id' => $out['page_id'],
                'group_id' => $group_id,
                'title' => $_POST['title'],
                'content' => $_POST['content'],
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemovePage() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'members_pages',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_members_db_connection'], 'members_pages',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('members_remove_page', [
                'id' => $_POST['id'],
                'result' => $out['count']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdatePage() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $page_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'members_pages',
            [['id', '=', $page_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['content'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_members_db_connection'], 'members_pages',
                [
                    'title' => $_POST['title'],
                    'content' => $_POST['content'],
                ],
                [['id', '=', $page_id, PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('members_update_page', [
                'id' => $page_id,
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIAddPagesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $sub_id = $_POST['sub_id'] ? APP::Module('Crypt')->Decode($_POST['sub_id']) : 0;

        if ($sub_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_members_db_connection'], ['fetchColumn', 0],
                ['COUNT(id)'], 'members_pages_groups',
                [['id', '=', $sub_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_members_db_connection'], 'members_pages_groups',
                Array(
                    'id' => 'NULL',
                    'sub_id' => [$sub_id, PDO::PARAM_INT],
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );

            APP::Module('Triggers')->Exec('members_add_pages_group', [
                'id' => $out['group_id'],
                'sub_id' => $sub_id,
                'name' => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemovePagesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'members_pages_groups',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $this->RemovePagesGroup($_POST['id']);
            APP::Module('Triggers')->Exec('members_remove_pages_group', ['id' => $_POST['id']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdatePagesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $group_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_members_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'members_pages_groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['name'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_members_db_connection'], 'members_pages_groups',
                ['name' => $_POST['name']],
                [['id', '=', $group_id, PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('members_update_pages_group', [
                'id' => $group_id,
                'name' => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_members_db_connection']], [['item', '=', 'module_members_db_connection', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('members_update_settings', [
            'db_connection' => $_POST['module_members_db_connection']
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

    public function APIGetPages() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        foreach ((array) $_POST['where'] as $key => $value) {
            $_POST['where'][$key][] = PDO::PARAM_STR;
        }

        echo json_encode(APP::Module('DB')->Select($this->settings['module_members_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], $_POST['select'], 'members_pages', $_POST['where']));
    }
}