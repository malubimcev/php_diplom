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
    
    function controllersAutoload($className) 
    {
        $filePath='./controller/'.$className.'.php';
        autoload($filePath);
    }

    function viewsAutoload($className) 
    {
        $filePath='./view/'.$className.'.php';
        autoload($filePath);
    }
    
    spl_autoload_register('modelsAutoload');
    spl_autoload_register('controllersAutoload');
    spl_autoload_register('viewsAutoload');