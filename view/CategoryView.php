<?php
require_once 'autoload.php';

class CategoryView extends View
{
    private $template = 'adminPage.twig';
    
    public function __construct($template = '')
    {
        if (!empty($template)) {
            $this -> template = $template;
        }
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template, array('categories' => $data));
    }

}//end class CategoryView