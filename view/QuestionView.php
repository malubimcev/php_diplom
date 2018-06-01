<?php
require_once 'view/View.php';

class QuestionView extends View
{
    private $template = 'questions.html';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template1, array('questions' => $data));
    }

}//end class QuestionView