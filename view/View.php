<?php
require_once 'vendor/autoload.php';
require_once 'autoload.php';

class View
{

    private $loader = NULL;
    private $twig = NULL;
    
    public function __construct() {
        $this -> loader = new Twig_Loader_Filesystem('./template');
        $this -> twig = new Twig_Environment($this -> loader, array(
            'cache' => 'cache',
            'auto_reload' => true
        ));
    }
    
    public function render($template, $data = NULL)
    {
        echo $this -> twig -> render($template, $data);
    }
    
}//end class View
