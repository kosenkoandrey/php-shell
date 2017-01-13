<?
class Rating {

    public $settings;
    public $types = ['mail'];
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_rating_db_connection'
        ]);
    }
    
    public function Admin() {
        return APP::Render('rating/admin/nav', 'content');
    }
    
    
    public function SetRating() {
        if (array_search(APP::Module('Routing')->get['item'], $this->types) === false) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        
        $id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['id']);
        $rating_id = false;

        switch (APP::Module('Routing')->get['item']) {
            case 'mail':
                if (APP::Module('DB')->Select(
                    APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                    ['COUNT(id)'], 'mail_log',
                    [['id', '=', $id, PDO::PARAM_INT]]
                )) {
                    $user = APP::Module('DB')->Select(
                        APP::Module('Mail')->settings['module_mail_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['user'], 'mail_log',
                        [['id', '=', $id, PDO::PARAM_INT]]
                    );
                    
                    if ($rating_id = APP::Module('DB')->Select(
                        $this->settings['module_rating_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['id'], 'rating',
                        [
                            ['item', '=', APP::Module('Routing')->get['item'], PDO::PARAM_STR],
                            ['object', '=', $id, PDO::PARAM_INT],
                            ['user', '=', $user, PDO::PARAM_INT]
                        ]
                    )) {
                        if (APP::Module('DB')->Select(
                            $this->settings['module_rating_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                            ['COUNT(id)'], 'rating',
                            [
                                ['item', '=', APP::Module('Routing')->get['item'], PDO::PARAM_STR],
                                ['object', '=', $id, PDO::PARAM_INT],
                                ['user', '=', $user, PDO::PARAM_INT],
                                ['comment', '=', '', PDO::PARAM_INT]
                            ]
                        )) {
                            $result = 'comment';
                        } else {
                            $result = 'exist';
                        }
                    } else {
                        $rating_id = APP::Module('DB')->Insert(
                            $this->settings['module_rating_db_connection'], 'rating',
                            [
                                'id' => 'NULL',
                                'item' => [APP::Module('Routing')->get['item'], PDO::PARAM_STR],
                                'object' => [$id, PDO::PARAM_INT],
                                'rating' => [APP::Module('Routing')->get['rating'], PDO::PARAM_INT],
                                'user' => [$user, PDO::PARAM_INT],
                                'comment' => '""',
                                'up_date' => 'NOW()'
                            ]
                        );
                        
                        $result = 'success';
                    }
                } else {
                    $result = 'error';
                }
                break;
        }

        APP::Render('rating/result/' . APP::Module('Routing')->get['item'] . '/' . $result, 'include', $rating_id);
    }
    
    public function PostComment() {
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'state' => APP::Module('DB')->Select(
                $this->settings['module_rating_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                ['COUNT(id)'], 'rating',
                [
                    ['id', '=', $_POST['id'], PDO::PARAM_INT]
                ]
            ) ? APP::Module('DB')->Update($this->settings['module_rating_db_connection'], 'rating', [
                'comment' => $_POST['comment']
            ], [
                ['id', '=', $_POST['id'], PDO::PARAM_INT]
            ]) : false
        ]);
    }
    

    public function RenderShortcode($id, $data) {
        $data['letter']['html'] = str_replace(
            '[rating]', 
            APP::Render('rating/widgets/mail', 'content'), 
            $data['letter']['html']
        );
        
        return $data;
    }

}