<?
class Blog {
    
    const URI = 'blog';
    
    public $settings;
    public $uri;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_blog_db_connection',
            'module_blog_posts_on_page'
        ]);
        
        $this->uri = self::URI ? self::URI . '/' : '';
    }
    
    public function Admin() {
        return APP::Render('blog/admin/nav', 'content');
    }
    
    
    public function Index() {
        APP::Render('blog/index', 'include', [
            'articles' => $this->LastArticlesByGroups(7),
            'most_commented' => $this->MostCommentedArticles(5)
        ]);
    }
    
    public function Tag() {
        $out = [
            'articles' => $this->LastArticlesByGroups(7),
            'tag_articles' => $this->TagArticles(APP::Module('Routing')->get['tag']),
            'most_commented' => $this->MostCommentedArticles(5)
        ];
        
        APP::Render('blog/tag', 'include', $out);
    }
    
    public function Page() {
        $view = false;
        
        if (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_groups',
            [['uri', '=', APP::Module('Routing')->get['token'], PDO::PARAM_STR]]
        )) {
            $view = 'group';
            
            $group = APP::Module('DB')->Select(
                $this->settings['module_blog_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'sub_id', 'uri', 'page_title', 'h1_title', 'annotation', 'image_type', 'description', 'keywords', 'robots'], 'blog_groups',
                [['uri', '=', APP::Module('Routing')->get['token'], PDO::PARAM_STR]]
            );
            
            if (isset(APP::Module('Routing')->get['page'])) {
                if (APP::Module('Routing')->get['page'] == 0) {
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: " . APP::Module('Routing')->root . $this->uri . $group['uri']); 
                    exit; 
                }
            }
            
            $page = [
                'offset' => isset(APP::Module('Routing')->get['page']) ? (int) APP::Module('Routing')->get['page'] : 0,
                'max' => ceil(APP::Module('DB')->Select(
                    $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
                    ['COUNT(id)'], 'blog_articles',
                    [['group_id', '=', $group['id'], PDO::PARAM_INT]]
                ) / $this->settings['module_blog_posts_on_page'])
            ];

            if ((($page['offset'] + 1) > $page['max']) || ($page['offset'] < 0)) {
                header('HTTP/1.0 404 Not Found');
                exit;
            }

            $out = [
                'group' => $group,
                'path' => $this->RenderArticlesGroupsPath($group['sub_id']),
                'articles' => $this->LastArticlesByGroups(7),
                'group_articles' => $this->GroupArticles($group['id'], $page['offset'] * $this->settings['module_blog_posts_on_page'], $this->settings['module_blog_posts_on_page']),
                'most_commented' => $this->MostCommentedArticles(5),
                'page' => $page
            ];
        }
        
        if (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_articles',
            [['uri', '=', APP::Module('Routing')->get['token'], PDO::PARAM_STR]]
        )) {
            $view = 'article';
            
            $article = APP::Module('DB')->Select(
                $this->settings['module_blog_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                ['id', 'group_id', 'uri', 'page_title', 'h1_title', 'annotation', 'html_content', 'image_type', 'tags', 'description', 'keywords', 'robots', 'UNIX_TIMESTAMP(up_date) AS up_date'], 'blog_articles',
                [['uri', '=', APP::Module('Routing')->get['token'], PDO::PARAM_STR]]
            );
            
            $out = [
                'article' => $article,
                'path' => $this->RenderArticlesGroupsPath($article['group_id']),
                'articles' => $this->LastArticlesByGroups(7),
                'most_commented' => $this->MostCommentedArticles(5),
                'related_articles' => $this->RelatedArticles($article['group_id'], 4, [$article['id']]),
                'random_tags' => $this->RandomTags(10)
            ];
        }
        
        if ($view) {
            APP::Render('blog/' . $view, 'include', $out);
        } else {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
    }
    
    public function Image() {
        $image = explode(',', APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['image'], 'blog_' . APP::Module('Routing')->get['object'],
            [['uri', '=', APP::Module('Routing')->get['uri'], PDO::PARAM_STR]]
        ));

        $image_src = imagecreatefromstring(base64_decode($image[1]));
        
        $image_size = [
            imagesx($image_src),
            imagesy($image_src)
        ];
        
        $new_size = explode('x', APP::Module('Routing')->get['size']);
        
        if (count(array_diff($image_size, $new_size))) {
            $scale = min(
                $new_size[0] / $image_size[0],
                $new_size[1] / $image_size[1]
            );

            $image_size = [
                round($image_size[0] * $scale),
                round($image_size[1] * $scale)
            ];
        }

        $out_image = imagecreatetruecolor($image_size[0], $image_size[1]);
        $image_mime = explode(';', (explode(':', $image[0])[1]))[0];

        switch ($image_mime) {
            case 'image/jpg':
            case 'image/jpeg':
                $write_image = 'imagejpeg';
                $image_quality = 75;
                break;
            case 'image/gif':
                imagecolortransparent($out_image, imagecolorallocate($out_image, 0, 0, 0));
                $write_image = 'imagegif';
                $image_quality = null;
                break;
            case 'image/png':
                imagecolortransparent($out_image, imagecolorallocate($out_image, 0, 0, 0));
                imagealphablending($out_image, false);
                imagesavealpha($out_image, true);
                $write_image = 'imagepng';
                $image_quality = 9;
                break;
        }

        header('Pragma: public');
        header("Cache-Control: max-age=604800");
        header("Expires: " . date(DATE_RFC822,strtotime("1 week")));
        header("Content-type: " . $image_mime);
        header("Accept-Ranges: bytes");

        imagecopyresampled(
            $out_image,
            $image_src,
            0, 0, 0, 0,
            $image_size[0],
            $image_size[1],
            imagesx($image_src),
            imagesy($image_src)
        );
        
        ob_start();
        $write_image($out_image, NULL, $image_quality);
        $size = ob_get_length();
        header("Content-Length: " . $size);
        ob_end_flush();

        imagedestroy($image_src);
        imagedestroy($out_image);
    }
    
    public function About() {
        APP::Render('blog/about', 'include', [
            'articles' => $this->LastArticlesByGroups(7)
        ]);
    }
    
    public function Contacts() {
        APP::Render('blog/contacts', 'include', [
            'articles' => $this->LastArticlesByGroups(7)
        ]);
    }
    
    public function TOS() {
        APP::Render('blog/tos', 'include', [
            'articles' => $this->LastArticlesByGroups(7)
        ]);
    }
    
    public function Privacy() {
        APP::Render('blog/privacy', 'include', [
            'articles' => $this->LastArticlesByGroups(7)
        ]);
    }

    
    public function LastArticlesByGroups($count) {
        $out = [];

        foreach (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'uri', 'page_title', 'image_type'], 'blog_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        ) as $group) {
            $out[] = [
                $group['uri'], 
                $group['page_title'],
                $group['image_type'],
                APP::Module('DB')->Select(
                    $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
                    [
                        'blog_articles.uri', 
                        'blog_articles.page_title',
                        'blog_articles.h1_title',
                        'blog_articles.annotation',
                        'blog_articles.image_type',
                        'UNIX_TIMESTAMP(blog_articles.up_date) AS up_date',
                        'COUNT(DISTINCT comments_messages.id) as comments',
                        'COUNT(DISTINCT likes_list.id) as likes'
                    ], 'blog_articles',
                    [['group_id', '=', $group['id'], PDO::PARAM_INT]],
                    [
                        'left join/comments_messages' => [
                            ['comments_messages.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                            ['comments_messages.object_id', '=', 'blog_articles.id']
                        ],
                        'left join/likes_list' => [
                            ['likes_list.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                            ['likes_list.object_id', '=', 'blog_articles.id']
                        ]
                    ],
                    ['blog_articles.id'],
                    false,
                    ['blog_articles.id', 'DESC'],
                    [0, $count]
                )
            ];
        }
        
        return $out;
    }
    
    public function GroupArticles($group, $offset, $count) {
        return APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'blog_articles.uri', 
                'blog_articles.page_title',
                'blog_articles.annotation',
                'blog_articles.image_type',
                'UNIX_TIMESTAMP(blog_articles.up_date) AS up_date',
                'COUNT(DISTINCT comments_messages.id) as comments',
                'COUNT(DISTINCT likes_list.id) as likes'
            ], 'blog_articles',
            [['group_id', '=', $group, PDO::PARAM_INT]],
            [
                'left join/comments_messages' => [
                    ['comments_messages.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['comments_messages.object_id', '=', 'blog_articles.id']
                ],
                'left join/likes_list' => [
                    ['likes_list.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['likes_list.object_id', '=', 'blog_articles.id']
                ]
            ],
            ['blog_articles.id'],
            false,
            ['blog_articles.id', 'DESC'],
            [$offset, $count]
        );
    }
    
    public function TagArticles($tag) {
        return APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'blog_articles.uri', 
                'blog_articles.page_title',
                'blog_articles.annotation',
                'blog_articles.image_type',
                'UNIX_TIMESTAMP(blog_articles.up_date) AS up_date',
                'COUNT(DISTINCT comments_messages.id) as comments',
                'COUNT(DISTINCT likes_list.id) as likes'
            ], 'blog_articles',
            [['tags', 'REGEXP', '(,|^)' . $tag . '(,|$)', PDO::PARAM_STR]],
            [
                'left join/comments_messages' => [
                    ['comments_messages.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['comments_messages.object_id', '=', 'blog_articles.id']
                ],
                'left join/likes_list' => [
                    ['likes_list.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['likes_list.object_id', '=', 'blog_articles.id']
                ]
            ],
            ['blog_articles.id'],
            false,
            ['blog_articles.id', 'DESC']
        );
    }
    
    public function RelatedArticles($group_id, $count, $exclude) {
        return APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'blog_articles.uri', 
                'blog_articles.page_title',
                'blog_articles.annotation',
                'blog_articles.image_type',
                'UNIX_TIMESTAMP(blog_articles.up_date) AS up_date',
                'COUNT(DISTINCT comments_messages.id) as comments',
                'COUNT(DISTINCT likes_list.id) as likes'
            ], 'blog_articles',
            [
                ['blog_articles.group_id', '=', $group_id, PDO::PARAM_INT],
                ['blog_articles.id', 'NOT IN', $exclude, PDO::PARAM_INT]
            ],
            [
                'left join/comments_messages' => [
                    ['comments_messages.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['comments_messages.object_id', '=', 'blog_articles.id']
                ],
                'left join/likes_list' => [
                    ['likes_list.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['likes_list.object_id', '=', 'blog_articles.id']
                ]
            ],
            ['blog_articles.id'],
            false,
            ['blog_articles.id', 'DESC'],
            [0, $count]
        );
    }
    
    public function MostCommentedArticles($count) {
        return APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            [
                'blog_articles.id',
                'blog_articles.uri', 
                'blog_articles.page_title',
                'blog_articles.annotation',
                'blog_articles.image_type',
                'UNIX_TIMESTAMP(blog_articles.up_date) AS up_date',
                'COUNT(DISTINCT comments_messages.id) AS comments',
                'COUNT(DISTINCT likes_list.id) AS likes'
            ], 'blog_articles',
            false,
            [
                'left join/comments_messages' => [
                    ['comments_messages.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Comments')->settings['module_comments_db_connection'], ['fetchColumn', 0], ['id'], 'comments_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['comments_messages.object_id', '=', 'blog_articles.id']
                ],
                'left join/likes_list' => [
                    ['likes_list.object_type', '=', '"' . APP::Module('DB')->Select(APP::Module('Likes')->settings['module_likes_db_connection'], ['fetchColumn', 0], ['id'], 'likes_objects', [['name', '=', 'BlogArticle', PDO::PARAM_STR]]) . '"'],
                    ['likes_list.object_id', '=', 'blog_articles.id']
                ]
            ],
            ['blog_articles.id'],
            false,
            ['comments', 'DESC'],
            [0, $count]
        );
    }
    
    public function RandomTags($count) {
        $out = [];
        
        foreach ((array) APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['tags'], 'blog_articles'
        ) as $value) {
            foreach (explode(',', $value) as $tag) {
                if ($tag) $out[] = $tag;
            }
        }
        
        $out = array_unique($out);
        shuffle($out);
        array_splice($out, $count);

        return $out;
    }
    
    
    private function RenderArticlesGroupsPath($group) {
        return $this->GetArticlesGroupsPath(0, $this->RenderArticlesGroups(), $group);
    }
    
    private function GetArticlesGroupsPath($group, $data, $target, $path = Array()) {
        $path[$group] = $data['name'];

        if ($group == $target) {
            return $path;
        }

        if (count($data['groups'])) {
            foreach ($data['groups'] as $key => $value) {
                $res = $this->GetArticlesGroupsPath($key, $value, $target, $path);
                
                if ($res) {
                    return $res;
                }
            }
        }

        unset($path[$group]);
        return false;
    }
    
    private function RenderArticlesGroups() {
        $out = [
            0 => [
                'name' => '/',
                'groups' => []
            ]
        ];
        
        $article_groups = APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'sub_id', 'page_title'], 'blog_groups',
            [['id', '!=', 0, PDO::PARAM_INT]]
        );

        foreach ($article_groups as $group) {
            $out[$group['id']] = [
                'name' => $group['page_title'],
                'groups' => []
            ];
        }
        
        foreach ($article_groups as $group) {
            $out[$group['sub_id']]['groups'][$group['id']] = $group['page_title'];
        }

        return $this->GetArticlesGroups($out, $out[0]);
    }
    
    private function GetArticlesGroups($groups, $data) {
        if (count($data['groups'])) {
            foreach ($data['groups'] as $id => $name) {
                $data['groups'][$id] = $this->GetArticlesGroups($groups, $groups[$id]);
            }
        }
        
        return $data;
    }
    
    private function RemoveArticlesGroup($group) {
        foreach (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['id'], 'blog_groups',
            [['sub_id', '=', $group, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $this->RemoveArticlesGroup($value);
        }
        
        APP::Module('DB')->Delete(
            $this->settings['module_blog_db_connection'], 'blog_groups',
            [['id', '=', $group, PDO::PARAM_INT]]
        );
    }
    

    public function ManageArticles() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        $list = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'page_title'], 'blog_groups',
            [['sub_id', '=', $group_sub_id, PDO::PARAM_INT],['id', '!=', 0, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['group', $value['id'], $value['page_title']];
        }
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['id', 'page_title'], 'blog_articles',
            [['group_id', '=', $group_sub_id, PDO::PARAM_INT]]
        ) as $value) {
            $list[] = ['article', $value['id'], $value['page_title']];
        }

        APP::Render('blog/admin/articles/index', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderArticlesGroupsPath($group_sub_id),
            'list' => $list
        ]);
    }
    
    public function AddArticle() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        
        APP::Render('blog/admin/articles/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderArticlesGroupsPath($group_sub_id)
        ]);
    }
    
    public function EditArticle() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $article_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['article_id_hash']);
        
        APP::Render(
            'blog/admin/articles/edit', 'include', 
            [
                'article' => APP::Module('DB')->Select(
                    $this->settings['module_blog_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['uri', 'page_title', 'h1_title', 'annotation', 'html_content', 'image', 'tags', 'description', 'keywords', 'robots'], 'blog_articles',
                    [['id', '=', $article_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderArticlesGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function AddArticlesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;

        APP::Render('blog/admin/articles/groups/add', 'include', [
            'group_sub_id' => $group_sub_id,
            'path' => $this->RenderArticlesGroupsPath($group_sub_id)
        ]);
    }
    
    public function EditArticlesGroup() {
        $group_sub_id = (int) isset(APP::Module('Routing')->get['group_sub_id_hash']) ? APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_sub_id_hash']) : 0;
        $group_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['group_id_hash']);
        
        APP::Render(
            'blog/admin/articles/groups/edit', 'include', 
            [
                'group' => APP::Module('DB')->Select(
                    $this->settings['module_blog_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
                    ['uri', 'page_title', 'h1_title', 'annotation', 'image', 'description', 'keywords', 'robots'], 'blog_groups',
                    [['id', '=', $group_id, PDO::PARAM_INT]]
                ),
                'group_sub_id' => $group_sub_id,
                'path' => $this->RenderArticlesGroupsPath($group_sub_id)
            ]
        );
    }
    
    public function Settings() {
        APP::Render('blog/admin/settings');
    }
    

    public function APIAddArticle() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = $_POST['group_id'] ? APP::Module('Crypt')->Decode($_POST['group_id']) : 0;

        if ($group_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'blog_groups',
                [['id', '=', $group_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }
        
        if (empty($_POST['uri'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['page_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['h1_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if (empty($_POST['annotation'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (empty($_POST['description'])) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if (empty($_POST['keywords'])) {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }
        
        if (empty($_POST['robots'])) {
            $out['status'] = 'error';
            $out['errors'][] = 9;
        }

        if ($out['status'] == 'success') {
            $image_type = explode('/', explode(';', $_POST['image'])[0]);
            
            $out['article_id'] = APP::Module('DB')->Insert(
                $this->settings['module_blog_db_connection'], 'blog_articles',
                Array(
                    'id' => 'NULL',
                    'group_id' => [$group_id, PDO::PARAM_INT],
                    'uri' => [$_POST['uri'], PDO::PARAM_STR],
                    'page_title' => [$_POST['page_title'], PDO::PARAM_STR],
                    'h1_title' => [$_POST['h1_title'], PDO::PARAM_STR],
                    'annotation' => [$_POST['annotation'], PDO::PARAM_STR],
                    'html_content' => [$_POST['html_content'], PDO::PARAM_STR],
                    'image_type' => [$image_type[1], PDO::PARAM_STR],
                    'image' => [$_POST['image'], PDO::PARAM_STR],
                    'tags' => isset($_POST['tags']) ? [implode(',', $_POST['tags']), PDO::PARAM_STR] : '""',
                    'description' => [$_POST['description'], PDO::PARAM_STR],
                    'keywords' => [$_POST['keywords'], PDO::PARAM_STR],
                    'robots' => [$_POST['robots'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('blog_add_article', [
                'id' => $out['article_id'],
                'group_id' => $group_id,
                'uri' => $_POST['uri'],
                'page_title' => $_POST['page_title'],
                'h1_title' => $_POST['h1_title'],
                'annotation' => $_POST['annotation'],
                'html_content' => $_POST['html_content'],
                'image' => $_POST['image'],
                'tags' => isset($_POST['tags']) ? implode(',', $_POST['tags']) : '',
                'description' => $_POST['description'],
                'keywords' => $_POST['keywords'],
                'robots' => $_POST['robots']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveArticle() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_articles',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_blog_db_connection'], 'blog_articles',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('blog_remove_article', [
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
    
    public function APIUpdateArticle() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $article_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_articles',
            [['id', '=', $article_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['uri'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['page_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['h1_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }

        if (empty($_POST['annotation'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (empty($_POST['description'])) {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if (empty($_POST['keywords'])) {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }
        
        if ($out['status'] == 'success') {
            $image_type = explode('/', explode(';', $_POST['image'])[0]);
            
            APP::Module('DB')->Update(
                $this->settings['module_blog_db_connection'], 'blog_articles', 
                [
                    'uri' => $_POST['uri'],
                    'page_title' => $_POST['page_title'],
                    'h1_title' => $_POST['h1_title'],
                    'annotation' => $_POST['annotation'],
                    'html_content' => $_POST['html_content'],
                    'image_type' => $image_type[1],
                    'image' => $_POST['image'],
                    'tags' => isset($_POST['tags']) ? implode(',', $_POST['tags']) : '',
                    'description' => $_POST['description'],
                    'keywords' => $_POST['keywords'],
                    'robots' => $_POST['robots']
                ], 
                [['id', '=', $article_id, PDO::PARAM_INT]]
            );
            
            APP::Module('Triggers')->Exec('blog_update_article', [
                'id' => $article_id,
                'uri' => $_POST['uri'],
                'page_title' => $_POST['page_title'],
                'h1_title' => $_POST['h1_title'],
                'annotation' => $_POST['annotation'],
                'html_content' => $_POST['html_content'],
                'image' => $_POST['image'],
                'tags' => isset($_POST['tags']) ? implode(',', $_POST['tags']) : '',
                'description' => $_POST['description'],
                'keywords' => $_POST['keywords'],
                'robots' => $_POST['robots']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIAddArticlesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $sub_id = $_POST['sub_id'] ? APP::Module('Crypt')->Decode($_POST['sub_id']) : 0;

        if ($sub_id) {
            if (!APP::Module('DB')->Select(
                $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
                ['COUNT(id)'], 'blog_groups',
                [['id', '=', $sub_id, PDO::PARAM_INT]]
            )) {
                $out['status'] = 'error';
                $out['errors'][] = 1;
            }
        }
        
        if (empty($_POST['uri'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['page_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['h1_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['annotation'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image_data = getimagesize($_FILES['image']['tmp_name']);
            
            if (($image_data[0] != 1920) || ($image_data[1] != 500)) {
                $out['status'] = 'error';
                $out['errors'][] = 7;
            }
        } else {
            $out['status'] = 'error';
            $out['errors'][] = 6;
        }
        
        if (empty($_POST['description'])) {
            $out['status'] = 'error';
            $out['errors'][] = 8;
        }
        
        if (empty($_POST['keywords'])) {
            $out['status'] = 'error';
            $out['errors'][] = 9;
        }
        
        if (empty($_POST['robots'])) {
            $out['status'] = 'error';
            $out['errors'][] = 10;
        }
        
        if ($out['status'] == 'success') {
            $image = 'data:' . $image_data['mime'] . ';base64,' . base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $image_type = explode('/', $image_data['mime']);
            
            $out['group_id'] = APP::Module('DB')->Insert(
                $this->settings['module_blog_db_connection'], 'blog_groups',
                Array(
                    'id' => 'NULL',
                    'sub_id' => [$sub_id, PDO::PARAM_INT],
                    'uri' => [$_POST['uri'], PDO::PARAM_STR],
                    'page_title' => [$_POST['page_title'], PDO::PARAM_STR],
                    'h1_title' => [$_POST['h1_title'], PDO::PARAM_STR],
                    'annotation' => [$_POST['annotation'], PDO::PARAM_STR],
                    'image_type' => [$image_type[1], PDO::PARAM_STR],
                    'image' => [$image, PDO::PARAM_STR],
                    'description' => [$_POST['description'], PDO::PARAM_STR],
                    'keywords' => [$_POST['keywords'], PDO::PARAM_STR],
                    'robots' => [$_POST['robots'], PDO::PARAM_STR],
                    'up_date' => 'NOW()'
                )
            );
            
            APP::Module('Triggers')->Exec('blog_add_articles_group', [
                'id' => $out['group_id'],
                'sub_id' => $sub_id,
                'uri' => $_POST['uri'],
                'page_title' => $_POST['page_title'],
                'h1_title' => $_POST['h1_title'],
                'annotation' => $_POST['annotation'],
                'image' => $image,
                'description' => $_POST['description'],
                'keywords' => $_POST['keywords'],
                'robots' => $_POST['robots']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveArticlesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_groups',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $this->RemoveArticlesGroup($_POST['id']);
            APP::Module('Triggers')->Exec('blog_remove_articles_group', ['id' => $_POST['id']]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateArticlesGroup() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $group_id = APP::Module('Crypt')->Decode($_POST['id']);
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchColumn', 0], 
            ['COUNT(id)'], 'blog_groups',
            [['id', '=', $group_id, PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }

        if (empty($_POST['uri'])) {
            $out['status'] = 'error';
            $out['errors'][] = 2;
        }
        
        if (empty($_POST['page_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 3;
        }
        
        if (empty($_POST['h1_title'])) {
            $out['status'] = 'error';
            $out['errors'][] = 4;
        }
        
        if (empty($_POST['annotation'])) {
            $out['status'] = 'error';
            $out['errors'][] = 5;
        }
        
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $image_data = getimagesize($_FILES['image']['tmp_name']);
            
            if (($image_data[0] != 1920) || ($image_data[1] != 500)) {
                $out['status'] = 'error';
                $out['errors'][] = 6;
            }
        }
        
        if (empty($_POST['description'])) {
            $out['status'] = 'error';
            $out['errors'][] = 7;
        }
        
        if (empty($_POST['keywords'])) {
            $out['status'] = 'error';
            $out['errors'][] = 8;
        }
        
        if (empty($_POST['robots'])) {
            $out['status'] = 'error';
            $out['errors'][] = 9;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_blog_db_connection'], 'blog_groups', 
                [
                    'uri' => $_POST['uri'],
                    'page_title' => $_POST['page_title'],
                    'h1_title' => $_POST['h1_title'],
                    'annotation' => $_POST['annotation'],
                    'description' => $_POST['description'],
                    'keywords' => $_POST['keywords'],
                    'robots' => $_POST['robots']
                ], 
                [['id', '=', $group_id, PDO::PARAM_INT]]
            );
            
            if (is_uploaded_file($_FILES['image']['tmp_name'])) {
                $image = 'data:' . $image_data['mime'] . ';base64,' . base64_encode(file_get_contents($_FILES['image']['tmp_name']));
                $image_type = explode('/', $image_data['mime']);
                
                APP::Module('DB')->Update(
                    $this->settings['module_blog_db_connection'], 'blog_groups', 
                    [
                        'image' => $image,
                        'image_type' => $image_type[1]
                    ], 
                    [['id', '=', $group_id, PDO::PARAM_INT]]
                );
            }
            
            APP::Module('Triggers')->Exec('blog_update_articles_group', [
                'id' => $group_id,
                'uri' => $_POST['uri'],
                'page_title' => $_POST['page_title'],
                'h1_title' => $_POST['h1_title'],
                'annotation' => $_POST['annotation'],
                'image' => is_uploaded_file($_FILES['image']['tmp_name']) ? $image : false,
                'description' => $_POST['description'],
                'keywords' => $_POST['keywords'],
                'robots' => $_POST['robots']
            ]);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIArticlesTags() {
        $out = [];
        $tags = [];

        foreach ((array) APP::Module('DB')->Select(
            $this->settings['module_blog_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
            ['tags'], 'blog_articles',
            [['tags', 'LIKE', '%' . APP::Module('Routing')->get['q'] . '%']]
        ) as $value) {
            foreach (explode(',', $value) as $tag) $tags[] = $tag;
        }

        foreach ((array) array_filter(array_unique($tags), function($tag) {
            return preg_match('/^' . APP::Module('Routing')->get['q'] . '/', $tag);
        }) as $value) {
            $out[] = [
                'id' => $value,
                'text' => $value
            ];
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIUpdateSettings() {
        APP::Module('Registry')->Update(['value' => $_POST['module_blog_db_connection']], [['item', '=', 'module_blog_db_connection', PDO::PARAM_STR]]);
        APP::Module('Registry')->Update(['value' => $_POST['module_blog_posts_on_page']], [['item', '=', 'module_blog_posts_on_page', PDO::PARAM_STR]]);
        
        APP::Module('Triggers')->Exec('blog_update_settings', [
            'db_connection' => $_POST['module_blog_db_connection'],
            'posts_on_page' => $_POST['module_blog_posts_on_page']
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