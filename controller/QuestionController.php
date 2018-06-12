<?php

require_once 'autoload.php';
require_once 'controller/ControllerTraits.php';

class QuestionController extends Controller
{
    use ParsingTrait;
    
    private $questions = [];//список вопросов
    private $categories = [];//список тем
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = '';//имя шаблона для отображения
    
    public function add($params)
    {
        $userController = new UserController();
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $user = $userController -> getUser($this -> data);
            $this -> data['status'] = 0;
            $this -> data['user_id'] = $user['id'];
            $question -> add($this -> data);
            $this -> getList();
        }
        $question = NULL;
    }
    
    public function delete($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $question -> delete($this -> data['id']);
            $this -> getList();
        }
        $question = NULL;
    }

    public function update($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
//echo 'Q.update.params=';var_dump($this -> data);echo '++<br>';exit;
            $id = $this -> data['id'];
            $question -> update($id, $this -> data);
            $this -> getByCategory($this -> data['category_id']);
        }
        $question = NULL;
    }

    public function getList()
    {
        $category = new Category();
        $this -> categories = $category -> getList();
        $this -> getByCategory($this -> categories[0]);
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
        
    public function hide($params)
    {
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $this -> data['status'] = 2;
            $this -> updateStatus($this -> data['id'], $this -> data['status']);
        }
    }

    public function publicate($params)
    {
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $this -> data['status'] = 1;
            $this -> updateStatus($this -> data['id'], $this -> data['status']);
        }
    }

    public function getByCategory($params)
    {
        $this -> parseData($params, $this -> data);
        $question = new Question();
        $category = new Category();
        $this -> questions = $question -> getByCategory($this -> data['id']);
        $this -> categories = $category -> getList();
        $this -> setTemplate();
        $view = new QuestionView($this -> viewTemplate);
        $view -> render($this -> questions, $this -> categories);
        $question = NULL;
        $category = NULL;
    }
    
    public function getAllByCategory($params)
    {
        $this -> parseData($params, $this -> data);
        $question = new Question();
        $category = new Category();
        $this -> categories = $category -> getList();
        $this -> questions = $question -> getAllByCategory($this -> data['id']);
        $this -> setTemplate();
        $view = new QuestionView($this -> viewTemplate);
        $view -> render($this -> questions, $this -> categories);
        $question = NULL;
        $category = NULL;
    }

    public function getUnanswered()
    {
        $this -> parseData($params, $this -> data);
        $question = new Question();
        $category = new Category();
        $this -> categories = $category -> getList();
        $this -> questions = $question -> getUnanswered();
        $this -> setTemplate('Unanswered');
        $view = new QuestionView($this -> viewTemplate);
        $view -> render($this -> questions, $this -> categories);
        $question = NULL;
        $category = NULL;
    }
    
    private function updateStatus($id, $status)
    {
        $question = new Question();
        $question -> updateStatus($id, $status);
        $this -> getList();
        $question = NULL;
    }
    
    private function setTemplate($param = '')
    {
        $app = Application::get();
        if ($app -> isAdminMode()) {
            $this -> viewTemplate = 'questionsAdmin'.$param.'.twig';
        } else {
            $this -> viewTemplate = 'questions.twig';
        }
    }

}//end class QuestionController
