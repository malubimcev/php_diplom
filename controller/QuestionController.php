<?php

require_once 'Controller.php';
require_once 'controller/Application.php';
require_once 'controller/ControllerTraits.php';
require_once 'model/Question.php';
require_once 'view/QuestionView.php';

class QuestionController extends Controller
{
    use ParsingTrait;
    
    private $questions = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = '';//имя шаблона для отображения
    
    public function add($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                $this -> data['status'] = 0;
                $question -> add($this -> data);
                $this -> getList();
            }
        }
        $question = NULL;
    }
    
    public function delete($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                $question -> delete($this -> data);
                $this -> getList();
            }
        }
        $question = NULL;
    }

    public function update($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $id = $this -> data['id'];
            if (count($this -> errors) == 0) {
                $question -> update($id, $this -> data);
                $this -> getList();
            }
        }
        $question = NULL;
    }

    public function getList()
    {
        $question = new Question();
        $this -> questions = $question -> getList();
        if (!empty($this -> questions)) {
            $this -> setTemplate();
            $view = new QuestionView($this -> viewTemplate);
            $view -> render($this -> questions);
        }
        $question = NULL;
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
            $this -> update($this -> data);
        }
    }

    public function publicate($params)
    {
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $this -> data['status'] = 1;
            $this -> update($this -> data);
        }
    }

    public function getByCategory($params)
    {
        $question = new Question();
        $this -> questions = $question -> getList();
        if (!empty($this -> questions)) {
            $this -> setTemplate();
            $view = new QuestionView($this -> viewTemplate);
            $view -> render($this -> questions);
        }
        $question = NULL;
    }

    private function setTemplate()
    {
        $app = Application::get();
        if ($app -> isAdminMode()) {
            $this -> viewTemplate = 'questionsAdmin.twig';
        } else {
            $this -> viewTemplate = 'questionsAdmin.twig';
        }
    }

}//end class QuestionController
