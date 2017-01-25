<?
class Quiz {
    
    public $settings;
    
    function __construct($conf) {
        foreach ($conf['routes'] as $route) APP::Module('Routing')->Add($route[0], $route[1], $route[2]);
    }
    
    public function Init() {
        $this->settings = APP::Module('Registry')->Get([
            'module_quiz_db_connection'
        ]);
    }
    
    public function Admin() {
        return APP::Render('quiz/admin/nav', 'content');
    }

    public function Questions() {
        APP::Render('quiz/admin/questions/index');
    }
    
    public function AddQuestion() {
        APP::Render('quiz/admin/questions/add');
    }
    
    public function EditQuestion() {
        $question_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['question_id_hash']);
        APP::Render('quiz/admin/questions/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['cr_date','id','text'], 'quiz_questions',
            [['id', '=', $question_id, PDO::PARAM_INT]]
        ));
    }
    
    public function Answers() {
        APP::Render('quiz/admin/answers/index');
    }
    
    public function AddAnswer() {
        APP::Render('quiz/admin/answers/add');
    }
    
    public function EditAnswer() {
        $answer_id = (int) APP::Module('Crypt')->Decode(APP::Module('Routing')->get['answer_id_hash']);
        APP::Render('quiz/admin/answers/edit', 'include', APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_ASSOC], 
            ['cr_date','id','text', 'rating', 'correct'], 'quiz_answers',
            [['id', '=', $answer_id, PDO::PARAM_INT]]
        ));
    }

    public function UserAnswer() {
        $out= [];
        
        $token = json_decode(APP::Module('Crypt')->Decode(APP::Module('Routing')->get['token']), 1);
        $answer_id = APP::Module('Crypt')->Decode(APP::Module('Routing')->get['answer_id_hash']);

        if (!$user_id = APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['id'], 'users',
            [['email', '=', $token['email'], PDO::PARAM_INT]]
        )) {
            APP::Render('quiz/answer', 'include', ['state'=> 'error']);
            exit;
        }
        
        if (!APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['id'], 'quiz_answers',
            [['id', '=', $answer_id, PDO::PARAM_INT]]
        )) {
            APP::Render('quiz/answer', 'include', ['state'=> 'error']);
            exit;
        }
        
        $question_id = APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['question_id'], 'quiz_answers',
            [['id', '=', $answer_id, PDO::PARAM_INT]]
        );
                
        $quiz_questions_available = APP::Module('DB')->Select(
            APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['value'], 'users_tags',
            [
                ['user', '=', $user_id, PDO::PARAM_INT],
                ['item', '=', 'quiz_questions_available', PDO::PARAM_STR],
            ]
        );
        
        if (!in_array($question_id, explode(',', $quiz_questions_available))) {
            APP::Render('quiz/answer', 'include', ['state'=> 'unavailable']);
            exit;
        }

        if (APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['id'], 'quiz_user_answers',
            [
                ['user_id', '=', $user_id, PDO::PARAM_INT],
                ['answer_id', 'IN', APP::Module('DB')->Select(
                    $this->settings['module_quiz_db_connection'], ['fetchAll', PDO::FETCH_COLUMN], 
                    ['id'], 'quiz_answers',[['question_id', '=', $question_id, PDO::PARAM_INT]]
                ), PDO::PARAM_INT],
            ]
        )) { 
            APP::Render('quiz/answer', 'include', ['state'=> 'exist_answer']);
            exit;
        }

        $out['user_answer_id'] = APP::Module('DB')->Insert(
            $this->settings['module_quiz_db_connection'], 'quiz_user_answers',
            [
                'id' => 'NULL',
                'user_id'   => [$user_id, PDO::PARAM_INT],
                'answer_id' => [$answer_id, PDO::PARAM_INT],
                'cr_date' => 'NOW()'
            ]
        );
        
        APP::Module('Triggers')->Exec('quiz_add_question', [
            'id' => $out['user_answer_id'],
            'user_id'   => $user_id,
            'answer_id' => $answer_id
        ]);
        
        APP::Render('quiz/answer', 'include', ['state'=> 'success']);
    }
    
    
    public function AnswersOnLastQuestions($recepient_id) {
        $answers_on_last_questions = Array();
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['quiz_user_answers.answer_id','quiz_answers.question_id','quiz_answers.rating','quiz_answers.correct'],
            'quiz_user_answers',
            [['quiz_user_answers.user_id', '=', $recepient_id, PDO::PARAM_INT]],
            [
                'join/quiz_answers' => [
                    ['quiz_answers.id', '=', 'quiz_user_answers.answer_id'],
                    ['quiz_answers.question_id', 'IN', '(' . APP::Module('DB')->Select(
                        APP::Module('Users')->settings['module_users_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
                        ['value'], 'users_tags',
                        [
                            ['user', '=', $recepient_id, PDO::PARAM_INT],
                            ['item', '=', 'quiz_questions_available', PDO::PARAM_STR],
                        ]
                    ) . ')']
                ]
            ],
            ['quiz_user_answers.id']
        ) as $answer) {
            $answers_on_last_questions[$answer['question_id']][$answer['answer_id']] = Array($answer['answer_id'], $answer['rating'], $answer['correct']);
        }
        
        return $answers_on_last_questions;
    }
    
    public function UserRating($user_id, $questions) {
        return APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['SUM(quiz_user_answers.rating)'], 'quiz_user_answers',
            [['quiz_user_answers.user_id', '=', $user_id, PDO::PARAM_INT]],
            [
                'join/quiz_answers' => [
                    ['quiz_answers.id', '=', 'quiz_user_answers.answer_id'],
                    ['quiz_answers.question_id', 'IN', '(' . implode(',', $questions) . ')']
                ]
            ],
            ['quiz_user_answers.user_id']
        );
    }
         
    public function APIQuestionsList() {
        $rows = [];
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['cr_date','id','text'], 'quiz_questions',
            $_POST['search'] ? [['text', 'LIKE', '%' . $_POST['search'] . '%' ]] : false,
            false,
            false, 
            false,
            [$_POST['sort_by'], $_POST['sort_direction']],
            $_POST['rows'] === -1 ? false : [($_POST['current'] - 1) * $_POST['rows'], $_POST['rows']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rows'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_quiz_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'quiz_questions', $_POST['search'] ? [['id', 'LIKE', $_POST['search'] . '%' ]] : false)
        ]);
        exit;
    }
    
    public function APICreateQuestion() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if ($out['status'] == 'success') {
            $out['question_id'] = APP::Module('DB')->Insert(
                $this->settings['module_quiz_db_connection'], 'quiz_questions',
                [
                    'id' => 'NULL',
                    'text' => [$_POST['text'], PDO::PARAM_STR],
                    'cr_date' => 'NOW()'
                ]
            );
            
            APP::Module('Triggers')->Exec('quiz_add_question', [
                'id' => $out['question_id'],
                'text' => $_POST['text']
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveQuestion() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'quiz_questions',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_quiz_db_connection'], 'quiz_questions',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('quiz_remove_question', [
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
    
    public function APIUpdateQuestion() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $question_id = (int) APP::Module('Crypt')->Decode($_POST['id']);
        
        if(!APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(*)'], 'quiz_questions',
            [['id', '=', $question_id, PDO::PARAM_INT]]
        )){
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_quiz_db_connection'], 'quiz_questions',
                [
                    'text' => $_POST['text'],
                ],
                [['id', '=', $question_id, PDO::PARAM_INT]]
            );
        }
        
        APP::Module('Triggers')->Exec('quiz_update_question', [
            'id' => $question_id,
            'text' => $_POST['text']
        ]);
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIAnswersList() {
        $rows = [];
        
        $question_id = (int) APP::Module('Crypt')->Decode($_POST['question_id']);
        
        $where[] = ['question_id', '=', $question_id, PDO::PARAM_INT];
        $_POST['search'] ? $where[] = ['text', 'LIKE', '%' . $_POST['search'] . '%' ] : '';
        
        foreach (APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetchAll', PDO::FETCH_ASSOC], 
            ['cr_date','id','text', 'correct', 'rating', 'question_id'], 'quiz_answers',
            $where,
            false,
            false, 
            false,
            [$_POST['sort_by'], $_POST['sort_direction']],
            $_POST['rows'] === -1 ? false : [($_POST['current'] - 1) * $_POST['rows'], $_POST['rows']]
        ) as $row) {
            $row['id_token'] = APP::Module('Crypt')->Encode($row['id']);
            $row['question_id_token'] = APP::Module('Crypt')->Encode($row['question_id']);
            array_push($rows, $row);
        }
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode([
            'current' => $_POST['current'],
            'rowCount' => $_POST['rows'],
            'rows' => $rows,
            'total' => APP::Module('DB')->Select($this->settings['module_quiz_db_connection'], ['fetchColumn', 0], ['COUNT(id)'], 'quiz_answers', $where)
        ]);
        exit;
    }
    
    public function APICreateAnswer() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        if ($out['status'] == 'success') {
            $out['answer_id'] = APP::Module('DB')->Insert(
                $this->settings['module_quiz_db_connection'], 'quiz_answers',
                [
                    'id'            => 'NULL',
                    'question_id'   => [$_POST['question_id'], PDO::PARAM_INT],
                    'text'          => [$_POST['text'], PDO::PARAM_STR],
                    'rating'        => [$_POST['rating'], PDO::PARAM_INT],
                    'correct'       => [$_POST['correct'], PDO::PARAM_STR],
                    'cr_date'       => 'NOW()'
                ]
            );
            
            APP::Module('Triggers')->Exec('quiz_add_answer', [
                'id'            => $out['answer_id'],
                'text'          => $_POST['text'],
                'question_id'   => $_POST['question_id'],
                'rating'        => $_POST['rating'],
                'correct'       => $_POST['correct'],
            ]);
        }

        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
    public function APIRemoveAnswer() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];

        if (!APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetchColumn', 0],
            ['COUNT(id)'], 'quiz_answers',
            [['id', '=', $_POST['id'], PDO::PARAM_INT]]
        )) {
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            $out['count'] = APP::Module('DB')->Delete(
                $this->settings['module_quiz_db_connection'], 'quiz_answers',
                [['id', '=', $_POST['id'], PDO::PARAM_INT]]
            );

            APP::Module('Triggers')->Exec('quiz_remove_answer', [
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
    
    public function APIUpdateAnswer() {
        $out = [
            'status' => 'success',
            'errors' => []
        ];
        
        $answer_id = (int) APP::Module('Crypt')->Decode($_POST['id']);
        
        if(!APP::Module('DB')->Select(
            $this->settings['module_quiz_db_connection'], ['fetch', PDO::FETCH_COLUMN], 
            ['COUNT(*)'], 'quiz_answers',
            [['id', '=', $answer_id, PDO::PARAM_INT]]
        )){
            $out['status'] = 'error';
            $out['errors'][] = 1;
        }
        
        if ($out['status'] == 'success') {
            APP::Module('DB')->Update(
                $this->settings['module_quiz_db_connection'], 'quiz_answers',
                [
                    'text'      => $_POST['text'],
                    'rating'    => $_POST['rating'],
                    'correct'   => $_POST['correct']
                ],
                [['id', '=', $answer_id, PDO::PARAM_INT]]
            );
        }
        
        APP::Module('Triggers')->Exec('quiz_update_answer', [
            'id' => $answer_id,
            'text' => $_POST['text'],
            'rating'    => $_POST['rating'],
            'correct'   => $_POST['correct']
        ]);
        
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Allow-Origin: ' . APP::$conf['location'][1]);
        header('Content-Type: application/json');
        
        echo json_encode($out);
        exit;
    }
    
}
