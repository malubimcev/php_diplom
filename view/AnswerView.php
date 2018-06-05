<?php
require_once 'view/View.php';

class AnswerView extends View
{
    private $template = 'adminPage.html';
    
    public function __construct($template = '')
    {
        if (!empty($template)) {
            $this -> template = $template;
        }
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template, array('answers' => $data));
    }

}//end class AnswerView