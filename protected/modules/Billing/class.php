<?
class Billing {

    public $settings;
    private $products_search;
    private $products_actions;

    function __construct($conf) {
        foreach ($conf['routes'] as $route)
            APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_billing_db_connection',
            'module_users_db_connection'
        ]);

        $this->products_search  = new ProductsSearch();
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
                case 'intersect': return array_keys((array) call_user_func_array('array_intersect_key', $out));
                    break;
                case 'merge': return array_keys((array) call_user_func_array('array_replace', $out));
                    break;
            }
        } else {
            return array_keys($out[0]);
        }
    }

    public function ManageProducts() {
        APP::Render('billing/admin/products/index');
    }

    public function AddProduct() {
        APP::Render(
            'billing/admin/products/add', 'include', [
            'products' => APP::Module('DB')->Select(
                $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['name', 'amount', 'access_link', 'descr_link', 'members_access', 'related_products', 'id'], 'billing_products'
            ),
        ]);
    }

    public function EditProduct() {
        $product_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['product_id_hash']);

        $product  = APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['name', 'amount', 'access_link', 'descr_link', 'members_access', 'related_products', 'plus_products'], 'billing_products', [['id', '=', $product_id, PDO::PARAM_INT]]
        );
        $product['plus_products'] = json_decode($product['plus_products'], true);

        $products = [];
        foreach (APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['name', 'amount', 'access_link', 'descr_link', 'members_access', 'related_products', 'id'], 'billing_products'
        ) as $item) {
            $products[$item['id']] = $item;
        }

        APP::Render(
            'billing/admin/products/edit', 'include', [
            'product'  => $product,
            'products' => $products,
            ]
        );
    }

    public function Settings() {
        APP::Render('billing/admin/settings');
    }

    public function APISearchProducts() {
        $request = json_decode(file_get_contents('php://input'), true);
        $out     = $this->ProductsSearch(json_decode($request['search'], 1));
        $rows    = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'name', 'amount', 'up_date'], 'billing_products', [['id', 'IN', $out, PDO::PARAM_INT]], false, false, false, [$request['sort_by'], $request['sort_direction']], [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['product_id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current'  => $request['current'],
            'rowCount' => $request['rows'],
            'rows'     => $rows,
            'total'    => count($out)
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
                $this->settings['module_billing_db_connection'], 'billing_products', [
                    'id'               => 'NULL',
                    'name'             => [$_POST['name'], PDO::PARAM_STR],
                    'amount'           => [$_POST['amount'], PDO::PARAM_INT],
                    'members_access'   => [$_POST['members_access'], PDO::PARAM_STR],
                    'related_products' => [$_POST['related_products'], PDO::PARAM_STR],
                    'plus_products'    => [json_encode($_POST['plus_products']), PDO::PARAM_STR],
                    'access_link'      => [$_POST['access_link'], PDO::PARAM_STR],
                    'descr_link'       => [$_POST['descr_link'], PDO::PARAM_STR],
                    'up_date'          => 'NOW()'
                ]
            );

            APP::Module('Triggers')->Exec('add_product', [
                'id'               => $out['product_id'],
                'name'             => $_POST['name'],
                'amount'           => $_POST['amount'],
                'members_access'   => $_POST['members_access'],
                'related_products' => $_POST['related_products'],
                'plus_products'    => $_POST['plus_products']
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
            $this->settings['module_billing_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'billing_products', [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_billing_db_connection'], 'billing_products', [['id', '=', $_POST['id'], PDO::PARAM_INT]]
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
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if ($out['status'] == 'success') {
            APP::Module('DB')->Update($this->settings['module_billing_db_connection'], 'billing_products',
                [
                    'access_link'      => $_POST['access_link'],
                    'descr_link'       => $_POST['descr_link'],
                    'name'             => $_POST['name'],
                    'amount'           => $_POST['amount'],
                    'members_access'   => $_POST['members_access'],
                    'related_products' => $_POST['related_products'],
                    'plus_products'    => json_encode($_POST['plus_products'])
                ],
                [['id', '=', $product_id, PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('update_product', [
                'id'               => $product_id,
                'name'             => $_POST['name'],
                'amount'           => $_POST['amount'],
                'members_access'   => $_POST['members_access'],
                'related_products' => $_POST['related_products'],
                'plus_products'    => $_POST['plus_products']
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

    public function AddMemberAccess($user_id, $products) {
        $out['access_id'] = [];

        foreach (APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['members_access'], 'billing_products', [['id', 'IN', $products, PDO::PARAM_STR]]
        ) as $product) {
            $items = explode(',', $product['members_access']);

            foreach ($items as $item) {
                $out['access_id'][] = APP::Module('DB')->Insert(
                    $this->settings['module_members_db_connection'], 'members_access', Array(
                        'id'      => 'NULL',
                        'user_id' => [$user_id, PDO::PARAM_INT],
                        'item'    => [substr($item, 0, 1), PDO::PARAM_STR],
                        'item_id' => [substr($item, 1), PDO::PARAM_INT],
                        'cr_date' => 'NOW()'
                    )
                );
            }
        }

        APP::Module('Triggers')->Exec('add_members_access', [
            'user_id'  => $user_id,
            'products' => $products
        ]);

        return $out;
    }

    public function AddRelatedProducts($user_id, $products) {
        $out['access_id'] = [];

        foreach ($products as $product) {
            $out['access_id'][] = APP::Module('DB')->Insert(
                $this->settings['module_members_db_connection'], 'related_products', Array(
                    'id'         => 'NULL',
                    'user_id'    => [$user_id, PDO::PARAM_INT],
                    'product_id' => [$product, PDO::PARAM_STR]
                )
            );
        }

        APP::Module('Triggers')->Exec('add_related_products', [
            'user_id'  => $user_id,
            'products' => $products
        ]);

        return $out;
    }

    public function AddPlusProducts($data) {
        APP::Module('DB')->Insert(
            $this->settings['module_members_db_connection'], 'billing_invoices_products', Array(
                'id'         => 'NULL',
                'invoice_id' => [$invoice_id, PDO::PARAM_INT],
                'product_id' => [$product[0], PDO::PARAM_INT],
                'price'      => [$product[1], PDO::PARAM_INT]
            )
        );
    }

    public function ManageInvoices() {
        APP::Render('billing/admin/invoices/index');
    }

    public function AddInvoice() {
        APP::Render(
            'billing/admin/invoices/add', 'include', [
            'products_list' => APP::Module('DB')->Select(
                $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['name', 'amount', 'id'], 'billing_products'
            ),
        ]);
    }

    public function EditInvoice() {
        $invoice_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['invoice_id_hash']);

        $products_counter = 0;

        // Формирование списка продуктов
        $products_list = Array();

        foreach (APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['name', 'amount', 'id'], 'billing_products'
        ) as $product) {
            $products_list[$product['id']] = [
                'name'   => '[' . $product['id'] . '] ' . $product['name'],
                'amount' => $product['amount']
            ];
        }

        $products = APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['product_id', 'price', 'id'], 'billing_invoices_products', [['invoice_id', '=', $invoice_id, PDO::PARAM_INT]]
        );

        // Получение кол-ва продуктов в счете
        $products_counter = APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetch', PDO::FETCH_COLUMN], ['count(*)'], 'billing_invoices_products'
        );

        APP::Render(
            'billing/admin/invoices/edit', 
            'include', 
            [
                'invoice' => [
                    // Информация о счете
                    'main'     => APP::Module('DB')->Select(
                        $this->settings['module_billing_db_connection'], ['fetch', PDO::FETCH_ASSOC], ['user_id', 'state', 'amount', 'id', 'UNIX_TIMESTAMP(up_date) as up_date', 'UNIX_TIMESTAMP(cr_date) as cr_date'], 'billing_invoices', [['id', '=', $invoice_id, PDO::PARAM_INT]]
                    ),
                    // Список продуктов счета
                    'products' => $products
                ],
                // Список продуктов
                'products_list' => $products_list,
                // Счетчики
                'counters'      => [
                    'products' => count($products)
                ]
            ]
        );
    }

    public function CreateInvoice($user_id, $author, $products, $state = 'new') {
        $amount      = 0;
        $products_id = [];
        foreach ($products as $product) {
            $amount      += $product[1];
            $products_id = $product[0];
        }

        $invoice_id = APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices', Array(
                    'id'      => 'NULL',
                    'user_id' => [$user_id, PDO::PARAM_INT],
                    'amount'  => [$amount, PDO::PARAM_INT],
                    'state'   => [$state, PDO::PARAM_STR],
                    'author'  => [$author, PDO::PARAM_INT],
                    'up_date' => 'NOW()',
                    'cr_date' => 'NOW()'
                )
        );

        foreach ($products as $product) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_products', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$invoice_id, PDO::PARAM_INT],
                    'product_id' => [$product[0], PDO::PARAM_INT],
                    'price'      => [$product[1], PDO::PARAM_INT]
                )
            );
        }

        //Добавление дополнительных продуктов
        foreach (APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['billing_products.id', 'billing_products.plus_products'], 'billing_products', [['billing_products.id', 'IN', $products_id, PDO::PARAM_STR]]
        ) as $product) {
            $plus_products = json_decode($product['plus_products'], true);
            if (count($plus_products)) {
                foreach ($plus_products as $item) {
                    APP::Module('TaskManager')->Add(
                        'Billing', 'AddPlusProducts', date('Y-m-d H:i:s', strtotime($item['time'])), json_encode([$invoice_id, $item['id'], 0]), 'plus_products', 'wait'
                    );
                }
            }
        }

        $data = [
            'user_id'    => $user_id,
            'author'     => $author,
            'state'      => $state,
            'amount'     => $amount,
            'products'   => $products,
            'invoice_id' => $invoice_id
        ];

        APP::Module('DB')->Insert(
            $this->settings['module_billing_db_connection'], 'billing_invoices_tag', Array(
                'id'          => 'NULL',
                'invoice_id'  => [$invoice_id, PDO::PARAM_INT],
                'action'      => ['invoice_create', PDO::PARAM_STR],
                'action_data' => [json_encode($data), PDO::PARAM_STR],
                'cr_date'     => 'NOW()'
            )
        );

        APP::Module('Triggers')->Exec('create_invoice', $data);

        return $invoice_id;
    }

    public function APISearchInvoices() {
        $request = json_decode(file_get_contents('php://input'), true);
        $rows    = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], ['id', 'user_id', 'amount', 'author', 'state', 'up_date'], 'billing_invoices', false, false, false, false, [$request['sort_by'], $request['sort_direction']], [($request['current'] - 1) * $request['rows'], $request['rows']]
        ) as $row) {
            $row['invoice_id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode([
            'current'  => $request['current'],
            'rowCount' => $request['rows'],
            'rows'     => $rows,
            'total'    => count([])
        ]);
        exit;
    }

    public function APIAddInvoice() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', $_POST['invoice']['user_id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', APP::Module('Users')->user['id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 2;
        }

        if ($out['status'] == 'success') {
            $out['invoice_id'] = $this->CreateInvoice($_POST['invoice']['user_id'], APP::Module('Users')->user['id'], $_POST['invoice']['products'], $_POST['invoice']['state']);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateInvoice() {
        $out = [
            'status'     => 'success',
            'errors'     => [],
            'invoice_id' => $_POST['invoice']['id']
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_billing_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'billing_invoices', [['id', '=', $_POST['invoice']['id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 1;
        }

        if (!APP::Module('DB')->Select(
            $this->settings['module_users_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'users', [['id', '=', $_POST['invoice']['user_id'], PDO::PARAM_INT]]
        )) {
            $out['status']   = 'error';
            $out['errors'][] = 2;
        }

        // Calculate invoice amount
        $amount = 0;

        if (isset($_POST['invoice']['products'])) {
            foreach ((array) $_POST['invoice']['products'] as $product) {
                $amount += (int) $product[1];
            }
        }

        // Update invoice
        APP::Module('DB')->Update($this->settings['module_billing_db_connection'],
            'billing_invoices',
            [
                'user_id' => $_POST['invoice']['user_id'],
                'amount'  => $amount,
                'state'   => $_POST['invoice']['state'],
                'up_date' => date('Y-m-d H:i:s')
            ], 
            [['id', '=', $_POST['invoice']['id'], PDO::PARAM_INT]]
        );

        // Remove invoice products
        APP::Module('DB')->Delete(
            $this->settings['module_billing_db_connection'], 'billing_invoices_products', [['invoice_id', '=', $_POST['invoice']['id'], PDO::PARAM_INT]]
        );

        // Insert invoice products
        foreach ($_POST['invoice']['products'] as $product) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_products', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice']['id'], PDO::PARAM_INT],
                    'product_id' => [$product[0], PDO::PARAM_INT],
                    'price'      => [$product[1], PDO::PARAM_INT]
                )
            );
        }

        // Сохранение истории
        $data = [
            'user_id'    => $_POST['invoice']['user_id'],
            'state'      => $_POST['invoice']['state'],
            'amount'     => $amount,
            'products'   => $_POST['invoice']['products'],
            'invoice_id' => $_POST['invoice']['id']
        ];

        APP::Module('DB')->Insert(
            $this->settings['module_billing_db_connection'], 'billing_invoices_tag', Array(
                'id'          => 'NULL',
                'invoice_id'  => [$_POST['invoice']['id'], PDO::PARAM_INT],
                'action'      => ['update_invoice', PDO::PARAM_STR],
                'action_data' => [json_encode($data), PDO::PARAM_STR],
                'cr_date'     => 'NOW()'
            )
        );

        APP::Module('Triggers')->Exec('update_invoice', $data);

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function APIUpdateInvoicesDetails() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (isset($_POST['lastname'])) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_details', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice_id'], PDO::PARAM_INT],
                    'item'       => ['lastname', PDO::PARAM_STR],
                    'value'      => [$_POST['lastname'], PDO::PARAM_STR]
                )
            );
        }

        if (isset($_POST['firstname'])) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_details', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice_id'], PDO::PARAM_INT],
                    'item'       => ['firstname', PDO::PARAM_STR],
                    'value'      => [$_POST['firstname'], PDO::PARAM_STR]
                )
            );
        }

        if (isset($_POST['tel'])) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_details', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice_id'], PDO::PARAM_INT],
                    'item'       => ['tel', PDO::PARAM_STR],
                    'value'      => [$_POST['tel'], PDO::PARAM_STR]
                )
            );
        }

        if (isset($_POST['email'])) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_details', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice_id'], PDO::PARAM_INT],
                    'item'       => ['email', PDO::PARAM_STR],
                    'value'      => [$_POST['email'], PDO::PARAM_STR]
                )
            );
        }

        if (isset($_POST['comment'])) {
            APP::Module('DB')->Insert(
                $this->settings['module_billing_db_connection'], 'billing_invoices_details', Array(
                    'id'         => 'NULL',
                    'invoice_id' => [$_POST['invoice_id'], PDO::PARAM_INT],
                    'item'       => ['comment', PDO::PARAM_STR],
                    'value'      => [$_POST['comment'], PDO::PARAM_STR]
                )
            );
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');

        echo json_encode($out);
        exit;
    }

    public function PaymentMake() {
        $data['packages']                     = [];
        $data['products']                     = [];
        $data['coupon']['state']              = 'open';
        $data['coupon']['settings']['amount'] = 100;
        $data['invoice_details']              = [
            'lastname'  => '',
            'firstname' => '',
            'email'     => '',
            'tel'       => '',
            'comment'   => ''
        ];

        $data['invoice'] = APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'],
            ['fetch', PDO::FETCH_ASSOC],
            ['users.email', 'billing_invoices.id', 'billing_invoices.amount', 'billing_invoices.state', 'billing_invoices.author', 'billing_invoices.user_id'],
            'billing_invoices',
            [['billing_invoices.id', '=', 1, PDO::PARAM_STR]],
            [
                'left join/users' => [
                    ['users.id', '=', 'billing_invoices.user_id']
                ]
            ]
        );

        APP::Render('billing/payments/make', 'include', $data);
    }
}

class ProductsSearch {

    public function id($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], ['id'], 'billing_products', [['id', $settings['logic'], $settings['value'], PDO::PARAM_INT]]
        );
    }

    public function name($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], ['id'], 'billing_products', [['name', $settings['logic'], $settings['value'], PDO::PARAM_STR]]
        );
    }

    public function amount($settings) {
        return APP::Module('DB')->Select(
            APP::Module('Billing')->settings['module_billing_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], ['id'], 'billing_products', [['amount', $settings['logic'], $settings['value'], PDO::PARAM_INT]]
        );
    }
}

class ProductsActions {
    public function remove($id, $settings) {
        return APP::Module('DB')->Delete(APP::Module('Billing')->settings['module_billing_db_connection'], 'billing_products', [['id', 'IN', $id]]);
    }
}
