<?php

require_once 'Controller.php';
require_once 'model/Question.php';
require_once 'view/QuestionView.php';

class QuestionController extends Controller
{
    private $questions = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = 'questions.twig';
    
    public function add($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $question -> add($this -> data);
                $this -> getList();
                }
        }
    }
    
    public function delete($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $question -> delete($this -> data);
                $this -> getList();
            }
        }
    }

    public function update($id, $params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $question -> update($this -> data);
                $this -> getList();
            }
        }
    }

    public function getList()
    {
        $question = new Question();
        $this -> questions = $question -> getList();
        if (!empty($this -> questions)) {
            $view = new QuestionView($this->viewTemplate);
            $view -> render($this -> questions);
        }
        $question = NULL;
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
        
    public function sort($params)
    {
        $question = new Question();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $question -> update($this -> data);
                $this -> getList();
            }
        }
    }

    private function parseData($data)
    {
        if (isset($data['id']) && preg_match('/[0-9\s]+/', $data['id'])) {
            $this -> data['id'] = $data['id'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
        if (isset($data['user_name']) && preg_match('/[0-9A-z\s]+/', $data['user_name'])) {
            $this -> data['login'] = $data['user_name'];
        } else {
            $this -> errors['login'] = 'Error login';
        }
        //выборка и сортировка
        if (isset($data['sort'])) {
            switch ($data['sort_by']) {//выбираем поле сортировки
                case 'user_id':
                    $this -> data['sort_by'] = 'user_id';
                    break;
                case 'description':
                    $this -> data['sort_by']  = 'description';
                    break;
                default:
                    $this -> data['sort_by'] = 'date_added';
                    break;
            }
        }        
    }

}//end class QuestionController
