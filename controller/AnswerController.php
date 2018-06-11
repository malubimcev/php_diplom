<?php

require_once 'autoload.php';
require_once 'controller/ControllerTraits.php';

class AnswerController extends Controller
{
    use ParsingTrait;
    
    private $answers = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = 'answer.twig';
    private $newAnswerTemplate = 'answersAdmin.twig';
    
    public function add($params)
    {
        $answer = new Answer();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
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
            $this -> parseData($params, $this -> data);
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
            $this -> parseData($params, $this -> data);
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
    
    public function addAnswer($params)
    {
        $this -> parseData($params, $this -> data);
        $question = new Question();
        $questions = $question -> getById($this -> data['question_id']);
        $answer = new Answer();
        $answers = $answer -> getByQuestion($this -> data['question_id']);
//$answers = ['description' => 'jhbjgg', 'date_added' => '000'];//==================================================================
        $view = new AnswerView($this -> newAnswerTemplate);
        $view -> render($answers, $questions);
    }
        
}//end class AnswerController
