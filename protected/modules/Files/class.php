<?

class Files {

    public $settings;

    function __construct($conf) {
        foreach ($conf['routes'] as $route)
            APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_files_db_connection',
            'module_files_mime',
            'module_files_path'
        ]);
    }

    public function Admin() {
        return APP::Render('files/admin/nav', 'content');
    }

    private function RenderFilesGroupsPath($group) {
        return $this->GetFilesGroupsPath(0, $this->RenderFilesGroups(), $group);
    }

    private function GetFilesGroupsPath($group, $data, $target, $path = Array()) {
        $path[$group] = $data['name'];

        if ($group == $target) {
            return $path;
        }

        if (count($data['groups'])) {
            foreach ($data['groups'] as $key => $value) {
                $res = $this->GetFilesGroupsPath($key, $value, $target, $path);

                if ($res) {
                    return $res;
                }
            }
        }

        unset($path[$group]);
        return false;
    }

    private function RenderFilesGroups() {
        $out = [
            0 => [
                'name'   => '/',
                'groups' => []
            ]
        ];

        $file_groups = APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'sub_id', 'name'], 'files_groups', [['id', '!=', 0, PDO::PARAM_INT]]
        );

        foreach ($file_groups as $group) {
            $out[$group['id']] = [
                'name'   => $group['name'],
                'groups' => []
            ];
        }

        foreach ($file_groups as $group) {
            $out[$group['sub_id']]['groups'][$group['id']] = $group['name'];
        }

        return $this->GetFilesGroups($out, $out[0]);
    }

    private function GetFilesSubGroupsId($group_id) {
        $out = [];

        $out[] = APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_COLUMN], ['id'], 'files_groups', [['id', '=', $group_id, PDO::PARAM_INT]]
        );

        if (count($out)) {
            $file_groups = APP::Module('DB')->Select(
                $this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'sub_id'], 'files_groups', [['sub_id', '=', $group_id, PDO::PARAM_INT]]
            );

            foreach ($file_groups as $group) {
                if ($group['sub_id']) {
                    $out = array_merge($out, $this->GetFilesSubGroupsId($group['id']));
                }
            }
        }

        return $out;
    }

    private function GetFilesGroups($groups, $data) {
        if (count($data['groups'])) {
            foreach ($data['groups'] as $id => $name) {
                $data['groups'][$id] = $this->GetFilesGroups($groups, $groups[$id]);
            }
        }

        return $data;
    }

    private function RemoveFilesGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], ['id'], 'files_groups', [['sub_id', '=', $group, PDO::PARAM_INT], ['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveFilesGroup($value);
        }

        APP::Module('DB')->Delete(
            $this->settings['module_files_db_connection'], 'files_groups', [['id', '=', $group, PDO::PARAM_INT]]
        );
    }

    public function ManageFiles() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        $list = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'name'], 'files_groups', [['sub_id', '=', $group_sub_id, PDO::PARAM_INT], ['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['name']];
        }

        foreach (APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'title', 'type'], 'files', [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['file', $value['id'], $value['title'], $value['type']];
        }

        APP::Render('files/admin/file/index', 'include', [
            'group_sub_id' => $group_sub_id,
            'path'         => $this->RenderFilesGroupsPath($group_sub_id),
            'list'         => $list
        ]);
    }

    public function PreviewFile() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $file_id      = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['file_id_hash']);

        APP::Render(
            'files/admin/file/preview', 'include', [
            'file'         => APP::Module('DB')->Select(
                $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['title', 'type', 'id'], 'files', [['id', '=', $file_id, PDO::PARAM_INT]]
            ),
            'group_sub_id' => $group_sub_id,
            'path'         => $this->RenderFilesGroupsPath($group_sub_id)
            ]
        );
    }

    public function AddFile() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('files/admin/file/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path'         => $this->RenderFilesGroupsPath($group_sub_id)
        ]);
    }

    public function EditFile() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $file_id      = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['file_id_hash']);

        APP::Render(
            'files/admin/file/edit', 'include', [
                'file'         => APP::Module('DB')->Select(
                    $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['title', 'type', 'id'], 'files', [['id', '=', $file_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path'         => $this->RenderFilesGroupsPath($group_sub_id)
            ]
        );
    }

    public function AddFilesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('files/admin/file/groups/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path'         => $this->RenderFilesGroupsPath($group_sub_id)
        ]);
    }

    public function EditFilesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $group_id     = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);

        APP::Render(
            'files/admin/file/groups/edit', 'include', [
                'group'  => APP::Module('DB')->Select(
                    $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['name'], 'files_groups', [['id', '=', $group_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path'         => $this->RenderFilesGroupsPath($group_sub_id)
            ]
        );
    }

    public function Settings() {
        APP::Render('files/admin/settings');
    }

    public function APIAddFile() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $group_id = $_POST['group_id'] ? APP::Module('Crypt')->Decode($_POST['group_id']) : 0;

        if ($group_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_files_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'files_groups', [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status']   = 'error';
                $out['errors'][] = 1;
            }
        }

        if ($out['status'] == 'success') {
            $pathinfo = pathinfo($_FILES['file']['tmp_name'] . '/' . $_FILES['file']['name']);

            $out['file_id'] = APP::Module('DB')->Insert(
                $this->settings['module_files_db_connection'], 'files', [
                    'id'       => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'title'    => [$_POST['title'], PDO::PARAM_STR],
                    'type'     => [$_FILES['file']['type'], PDO::PARAM_STR],
                    'cr_date'  => 'NOW()'
                ]
            );

            if (!$this->FileUpload($_FILES['file'], $this->settings['module_files_path'] . $out['file_id'] . '.' . $pathinfo['extension'])) {
                APP::Module('DB')->Delete(
                    $this->settings['module_files_db_connection'], 'files', [['id', '=', $out['file_id'], PDO::PARAM_INT]]
                );
                $out['status']   = 'error';
                $out['errors'][] = 2;
            } else {
                APP::Module('Triggers')->Exec('files_add', [
                    'id'       => $out['file_id'],
                    'group_id' => $group_id,
                    'title'    => $_POST['title'],
                    'type'     => $_FILES['file']['type'],
                ]);
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemoveFile() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!$file = APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['id', 'type'], 'files', [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_files_db_connection'], 'files', [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            $extension = explode('/', $file['type']);

            if (file_exists($this->settings['module_files_path'] . $file['id'] . '.' . $extension[1])) {
                unlink($this->settings['module_files_path'] . $file['id'] . '.' . $extension[1]);
            }

            APP::Module('Triggers')->Exec('files_remove', [
                'id'     => $_POST['id'],
                'result' => $out['count']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateFile() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $file_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!$file = APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['id', 'type'], 'files', [['id', '=', $file_id, PDO::PARAM_INT]])
        ) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            if (isset($_FILES['file']) && $_FILES['file']['size']) {
                $pathinfo  = pathinfo($_FILES['file']['tmp_name'] . '/' . $_FILES['file']['name']);
                $extension = explode('/', $file['type']);

                if (file_exists($this->settings['module_files_path'] . $file['id'] . '.' . $extension[1])) {
                    unlink($this->settings['module_files_path'] . $file['id'] . '.' . $extension[1]);
                }

                if ($this->FileUpload($_FILES['file'], $this->settings['module_files_path'] . $file_id . '.' . $pathinfo['extension'])) {
                    APP::Module('DB')->Update(
                        $this->settings['module_files_db_connection'], 'files', [
                        'title' => $_POST['title'],
                        'type'  => $_FILES['file']['type'],
                        ], [['id', '=', $file_id, PDO::PARAM_INT]]
                    );

                    APP::Module('Triggers')->Exec('files_update', [
                        'id'    => $file_id,
                        'title' => $_POST['title'],
                        'type'  => $_FILES['file']['type'],
                    ]);
                } else {
                    $out['status']   = 'error';
                    $out['errors'][] = 2;
                }
            } else {
                APP::Module('DB')->Update(
                    $this->settings['module_files_db_connection'], 'files', [
                        'title' => $_POST['title']
                    ], [['id', '=', $file_id, PDO::PARAM_INT]]
                );

                APP::Module('Triggers')->Exec('files_update', [
                    'id'    => $file_id,
                    'title' => $_POST['title']
                ]);
            }
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIAddFilesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $sub_id = $_POST['sub_id'] ? APP::Module('Crypt')->Decode($_POST['sub_id']) : 0;

        if ($sub_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_files_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'files_groups', [['id', '=', $sub_id, PDO::PARAM_INT]]
            )) {
                $out['status']   = 'error';
                $out['errors'][] = 1;
            }
        }

        if (empty($_POST['name'])) {
            $out['status']   = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_files_db_connection'], 'files_groups', Array(
                    'id'      => 'NULL',
                    'sub_id'  => [$sub_id, PDO::PARAM_INT],
                    'name'    => [$_POST['name'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );

            APP::Module('Triggers')->Exec('files_add_group', [
                'id'     => $out['group_id'],
                'sub_id' => $sub_id,
                'name'   => $_POST['name']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemoveFilesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'files_groups', [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $this->RemoveFilesGroup($_POST['id']);
            APP::Module('Triggers')->Exec('files_remove_group', ['id' => $_POST['id']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateFilesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $group_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select(
            $this->settings['module_files_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'files_groups', [['id', '=', $group_id, PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['name'])) {
            $out['status']   = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_files_db_connection'], 'files_groups', ['name' => $_POST['name']], [['id', '=', $group_id, PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('files_update_group', [
                'id'   => $group_id,
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
        APP::Module('Registry')->Update(['value' => $_POST['module_files_mime']], [['item', '=', 'module_files_mime', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_files_path']], [['item', '=', 'module_files_path', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_files_db_connection']], [['item', '=', 'module_files_db_connection', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('files_update_settings', [
            'db_connection' => $_POST['module_files_db_connection'],
            'mime'          => $_POST['module_files_mime'],
            'path'          => $_POST['module_files_path']
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

    public function APIGetFiles() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        foreach ((array) $_POST['where'] as $key => $value) {
            $_POST['where'][$key][] = PDO::PARAM_STR;
        }

        echo json_encode(APP::Module('DB')->Select($this->settings['module_files_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], $_POST['select'], 'files', $_POST['where']));
    }

    public function FileUpload($file, $path) {
        $allowed      = [];
        $mime_allowed = preg_replace('~\r?\n~', "\n", $this->settings['module_files_mime']);
        $filetypes    = explode("\n", $mime_allowed);
        foreach ($filetypes as $filetype) {
            $allowed[] = trim($filetype);
        }

        if (in_array($file['type'], $allowed) && $file['size']) {
            return move_uploaded_file($file['tmp_name'], $path);
        } else {
            return false;
        }
    }

}
