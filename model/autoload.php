<?php
    
    function autoload($filePath)
    {
        if (file_exists($filePath)){
            require_once($filePath);
        }
    }
    
    function modelsAutoload($className) 
    {
        $filePath='./model/'.$className.'.php';
        autoload($filePath);
    }
    
    spl_autoload_register('modelsAutoload');
   