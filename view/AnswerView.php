<?php
require_once 'autoload.php';

class AnswerView extends View
{
    private $template = 'adminPage.twig';
    
    public function __construct($template = '')
    {
        if (!empty($template)) {
            $this -> template = $template;
        }
        parent::__construct();
    }
    
    public function render($answers, $question)
    {
        parent::render($this -> template, array('answers' => $answers, 'question' => $question));
    }

}//end class AnswerView