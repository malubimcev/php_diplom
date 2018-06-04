<?php
require_once 'view/View.php';

class UserView extends View
{
    //private $template = 'users.php';
    private $template = 'adminPage.html';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template, array('users' => $data));
    }

}//end class UserView