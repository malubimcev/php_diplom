<?php
require_once 'autoload.php';

class QuestionView extends View
{
    private $template = 'adminPage.twig';
    
    public function __construct($template = '')
    {
        if (!empty($template)) {
            $this -> template = $template;
        }
        parent::__construct();
    }
    
    public function render($questions, $categories)
    {
        parent::render($this -> template, array('questions' => $questions, 'categories' => $categories));
    }

}//end class QuestionView