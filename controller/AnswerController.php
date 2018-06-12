<?php

require_once 'autoload.php';
require_once 'controller/ControllerTraits.php';

class AnswerController extends Controller
{
    use ParsingTrait;
    
    private $answers = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $viewTemplate = 'answers.twig';
    private $newAnswerTemplate = 'answersAdmin.twig';
    
    public function add($params)
    {
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $answer = new Answer();
            $question = new Question();
            if (!isset($this -> data['status'])) {
                $this -> data['status'] = 2;
            } else {
                $this -> data['status'] = 1;
            }
            $answer -> add($this -> data);
            $question ->updateStatus($this -> data['question_id'], $this -> data['status']);
            $this -> addAnswer($this -> data);
        }
        $answer = NULL;
    }
    
    public function delete($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $idAdd = $answer -> delete($this -> data);
            $this -> getList;
        }
        $answer = NULL;
    }

    public function update($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            $idAdd = $answer -> update($this -> data);
            $this -> getList;
        }
        $answer = NULL;
    }
    
    public function getList()
    {
        $this -> getByQuestion($this -> data);
    }

    public function getByQuestion($params)
    {
        $this -> parseData($params, $this -> data);
        $questionModel = new Question();
        $question = [];
        $question = $questionModel -> getById($this -> data['question_id'])[0];
        $answerModel = new Answer();
        $this -> answers = $answerModel -> getByQuestion($question['id']);
        $view = new AnswerView($this -> viewTemplate);
        $view -> render($this -> answers, $question);
        $answer = NULL;
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
    
    public function addAnswer($params)
    {
        $this -> parseData($params, $this -> data);
        $questionModel = new Question();
        $question = [];
        $question = $questionModel -> getById($this -> data['question_id'])[0];
        $answerModel = new Answer();
        $this -> answers = $answerModel -> getByQuestion($question['id']);
        $view = new AnswerView($this -> newAnswerTemplate);
        $view -> render($this -> answers, $question);
    }
        
}//end class AnswerController