<?php
require_once 'controller/QuestionController.php';

class Application {

    static $app = NULL;
    private $adminMode = FALSE;
    
    public static function get()
    {
        if (self::$app === NULL) {
            self::$app = new Application();
        }
        return self::$app;
    }

    public function isAdminMode()
    {
        if (($this -> isAuthorized()) && ($this -> isAdmin())) {
            $this -> adminMode = TRUE;
        } else {
            $this -> adminMode = FALSE;
        }
        return $this -> adminMode;
    }

    public function run()
    {
        $questionController = new QuestionController();
        $questionController -> defaultAction();
    }
    
    private function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }
    
    private function isAdmin()
    {
        return ($_SESSION['user']['is_admin']);
    }

}//end class Application