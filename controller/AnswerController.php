<?php

require_once 'Controller.php';
require_once 'controller/ControllerTraits.php';
require_once 'model/Answer.php';
require_once 'view/AnswerView.php';

class AnswerController extends Controller
{
    use ParsingTrait;
    
    private $answers = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = 'answer.twig';
    
    public function add($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $answer -> add($this -> data);
                $this -> getList;
            }
        }
        $answer = NULL;
    }
    
    public function delete($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $answer -> delete($this -> data);
                $this -> getList;
            }
        }
        $answer = NULL;
    }

    public function update($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $answer -> update($this -> data);
                $this -> getList;
            }
        }
        $answer = NULL;
    }

    public function getList()
    {
        $answer = new Answer();
        $this -> answers = $answer -> getList();
        if (!empty($this -> answers)) {
            $view = new AnswerView($this -> viewTemplate);
            $view -> render($this -> answers);
        }
        $answer = NULL;
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
        
    private function parseData($data)
    {
        if (isset($data['id']) && preg_match('/[0-9\s]+/', $data['id'])) {
            $this -> data['id'] = $data['id'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
        if (isset($data['question_id']) && preg_match('/[0-9\s]+/', $data['question_id'])) {
            $this -> data['question_id'] = $data['question_id'];
        } else {
            $this -> errors['question_id'] = 'Error question';
        }
        if (isset($data['paramName']) && ($data['paramName'] == 'id')) {
            $this -> data['id'] = $data['paramValue'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
        
    }

}//end class AnswerController
