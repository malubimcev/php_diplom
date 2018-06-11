<?php

class Application {

    static $app = NULL;
    
    public static function get()
    {
        if (self::$app === NULL) {
            self::$app = new Application();
        }
        return self::$app;
    }

    public function isAdminMode()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return TRUE;
        } else {
            session_start();
            return (!empty($_SESSION['user']) && ($_SESSION['user']['is_admin'] !== 0));
        }
    }  

}//end class Application