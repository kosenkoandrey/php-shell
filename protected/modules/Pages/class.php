<?
class Pages {

    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }

    public function ProductGarderob100Sale() {
        $cookie_tunnel_subscribtion_id = isset($_COOKIE['tunnel_subscribtion_id']) ? $_COOKIE['tunnel_subscribtion_id'] : 0;
        $tunnel_subscribtion_id = isset(APP::Module('Routing')->get['token']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['token']) : $cookie_tunnel_subscribtion_id;

        if (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels_users',
            [['id', '=', $tunnel_subscribtion_id, PDO::PARAM_INT]]
        )) {
            setcookie('tunnel_subscribtion_id', $tunnel_subscribtion_id, time() + 31556926, '/', '.glamurnenko.ru');
        } else {
            APP::Render('pages/products/garderob100/0');
            exit;
        }
        
        if (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(id)'], 'tunnels_users',
            [
                ['id', '=', $tunnel_subscribtion_id, PDO::PARAM_INT],
                ['tunnel_id', '=', 57, PDO::PARAM_INT]
            ]
        )) {
            $tunnel_subscribtion = APP::Module('DB')->Select(
                APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'user_id', 'state'], 'tunnels_users',
                [['id', '=', $tunnel_subscribtion_id, PDO::PARAM_INT]]
            );
        } else {
            APP::Render('pages/products/garderob100/0');
            exit;
        }
        
        if ($tunnel_subscribtion['state'] != 'active') {
            APP::Render('pages/products/garderob100/5', 'include', $tunnel_subscribtion);
            exit;
        }
        
        $tunnel_subscription_labels = [];
        
        foreach (APP::Module('DB')->Select(
            APP::Module('Tunnels')->settings['module_tunnels_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['label_id', 'token'], 'tunnels_tags',
            [['user_tunnel_id', '=', $tunnel_subscribtion_id, PDO::PARAM_INT]]
        ) as $label) {
            $tunnel_subscription_labels[$label['label_id']][] = $label['token'];
        }
        
        // Получил 6/7/8 письмо
        if (
            (array_search('34', $tunnel_subscription_labels['sendmail']) !== false) || 
            (array_search('35', $tunnel_subscription_labels['sendmail']) !== false) || 
            (array_search('36', $tunnel_subscription_labels['sendmail']) !== false)
        ) {
            APP::Render('pages/products/garderob100/4', 'include', $tunnel_subscribtion);
            exit;
        }

        // Получил 5 письмо
        if (array_search('33', $tunnel_subscription_labels['sendmail']) !== false) {
            APP::Render('pages/products/garderob100/3', 'include', $tunnel_subscribtion);
            exit;
        }

        // Получил 4 письмо
        if (array_search('32', $tunnel_subscription_labels['sendmail']) !== false) {
            APP::Render('pages/products/garderob100/2', 'include', $tunnel_subscribtion);
            exit;
        }

        // Получил 1/2/3 письмо
        if (
            (array_search('29', $tunnel_subscription_labels['sendmail']) !== false) || 
            (array_search('30', $tunnel_subscription_labels['sendmail']) !== false) || 
            (array_search('31', $tunnel_subscription_labels['sendmail']) !== false)
        ) {
            APP::Render('pages/products/garderob100/1', 'include', $tunnel_subscribtion);
            exit;
        }
    }
    
    public function ProductGarderob100Form() {
        APP::Render('pages/products/garderob100/form');
    }
    
}