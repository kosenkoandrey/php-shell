<?
class Billing {

    public $settings;

    private $products_search;
    private $products_actions;

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_billing_db_connection'
        ]);

        $this->products_search = new ProductsSearch();
        $this->products_actions = new ProductsActions();
    }

    public function Admin() {
        return APP::Render('billing/admin/nav', 'content');
    }


    public function ProductsSearch($rules) {
        $out = Array();

        foreach ((array) $rules['rules'] as $rule) {
            $out[] = array_flip((array) $this->products_search->{$rule['method']}($rule['settings']));
        }

        if (array_key_exists('children', (array) $rules)) {
            $out[] = array_flip((array) $this->ProductsSearch($rules['children']));
        }

        if (count($out) > 1) {
            switch ($rules['logic']) {
                case 'intersect': return array_keys((array) call_user_func_array('array_intersect_key', $out)); break;
                case 'merge': return array_keys((array) call_user_func_array('array_replace', $out)); break;
            }
        } else {
            return array_keys($out[0]);
        }
    }



    public function ManageProducts() {
        APP::Render('billing/admin/products/index');
    }

    public function AddProduct() {
        APP::Render('billing/admin/products/add');
    }

    public function EditProduct() {
        $product_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['product_id_hash']);

        APP::Render(
            'billing/admin/products/edit', 'include',
            [
                'product' => APP::Module('DB')->Select(
                    $this->settings['module_billing_db_connection'], ['fetch', PDO::FETCH_ASSOC],
                    ['name', 'amount', 'access_link', 'descr_link', 'members_access'], 'billing_products',
                    [['id', '=', $product_id, PDO::PARAM_INT]]
                ),
            ]
        );
    }

    public function Settings() {
        APP::Render('billing/admin/settings');
    }


    public function APISearchProducts() {
        $request = json_decode(file_get_contents('php://input'), true);
        $out = $this->ProductsSearch(json_decode($request['search'], 1));
        $rows = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC],
            ['id', 'name', 'amount', 'up_date'], 'billing_products',
            [['id', 'IN', $out, PDO::PARAM_INT]],
            false, false, false,
            [$request['sort_by'], $request['sort_direction']],
            [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['product_id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current' => $request['current'],
            'rowCount' => $request['rows'],
            'rows' => $rows,
            'total' => count($out)
        ]);
        exit;
    }

    public function APISearchProductsAction() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($this->products_actions->{$_POST['action']}($this->ProductsSearch(json_decode($_POST['rules'], 1)), isset($_POST['settings']) ? $_POST['settings'] : false));
        exit;
    }

    public function APIAddProduct() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if ($out['status'] == 'success') {
            $out['product_id'] = APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_products',
                Array(
                    'id' => 'NULL',
                    'name' => [$_POST['name'], PDO::PARAM_STR],
                    'amount' => [$_POST['amount'], PDO::PARAM_INT],
																				'members_access' => [$_POST['members_access'], PDO::PARAM_STR],
                    'access_link' => [$_POST['access_link'], PDO::PARAM_STR],
                    'descr_link' => [$_POST['descr_link'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );

            APP::Module('Triggers')->Exec('add_product', [
                'id' => $out['product_id'],
                'name' => $_POST['name'],
                'amount' => $_POST['amount'],
																'members_access' => $_POST['members_access'],
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIRemoveProduct() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'billing_products',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_billing_db_connection'], 'billing_products',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('remove_product', ['id' => $_POST['id'], 'result' => $out['count']]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateProduct() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        $product_id = APP::Module('Crypt')->Decode($_POST['id']);

        if (!APP::Module('DB')->Select($this->settings['module_billing_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'billing_products', [['id', '=', $product_id, PDO::PARAM_INT]])) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_billing_db_connection'], 'billing_products', [
                'access_link' => $_POST['access_link'],
                'descr_link' => $_POST['descr_link'],
                'name' => $_POST['name'],
                'amount' => $_POST['amount'],
																'members_access' => $_POST['members_access'],
            ], [
                ['id', '=', $product_id, PDO::PARAM_INT]
            ]);

            APP::Module('Triggers')->Exec('update_product', [
                'id' => $product_id,
                'name' => $_POST['name'],
                'amount' => $_POST['amount'],
																'members_access' => $_POST['members_access'],
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_billing_db_connection']], [['item', '=', 'module_billing_db_connection', PDO::PARAM_STR]]);

        APP::Module('Triggers')->Exec('update_billing_settings', [
            'db_connection' => $_POST['module_billing_db_connection']
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

class ProductsSearch {

    public function name($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'],
            ['fetchAll', PDO::FETCH_COLUMN],
            ['id'], 'billing_products',
            [['name', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }

    public function amount($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'],
            ['fetchAll', PDO::FETCH_COLUMN],
            ['id'], 'billing_products',
            [['amount', $settings['logic'], $settings['value'], PDO::PARAM_INT]]
        );
    }

}

class ProductsActions {

    public function remove($id, $settings) {
        return APP::Module('DB')->Delete(APP::Module('Billing')->settings['module_billing_db_connection'], 'billing_products', [['id', 'IN', $id]]);
    }

}