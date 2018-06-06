<?php
require_once 'Controller.php';
require_once 'model/User.php';
require_once 'controller/QuestionController.php';
require_once 'view/UserView.php';

class UserController extends Controller {

    private $data = [];
    private $errors = [];
    private $result ='';
    private $authorizedUser = NULL;
    private $loginPage = 'login.twig';
    private $userListPage = 'userList.twig';

    public function add($params)
    {
        $user = new User();
        if (count($params) > 0) {
            $this -> data = $this -> parseUserData($params);
            if (count($this -> errors) == 0) {
                if ($user -> add($this -> data)) {
                    $this -> getList();
                    $user = NULL;
                }
            }
        }
    }

    public function delete($id)
    {
        if (isset($id) && is_numeric($id)) {
            $user = new User();
            $isDelete = $user -> delete($id);
            if ($isDelete) {
                $this -> getList();
                $user = NULL;
            }
        }
    }

    public function update($id, $params)
    {
        $this -> data = $this -> parseUserData($params);
        if (empty($this -> errors)) {
            $user = new User();
            $user -> update($this -> data);
        }
    }

    public function getList()
    {
        $user = new User();
        $this -> data = $user -> getList();
        if (!empty($this -> data)) {
            $view = new UserView($this -> userListPage);
            $view -> render($this -> data);
        }
    }
    
    public function defaultAction()
    {
//$this -> getList();return;//========================================================================
        $view = new UserView($this -> loginPage);
        $this -> data['result'] = $this -> result;
        $view -> render($this -> data);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $this -> defaultAction();
    }

    public function login($data)
    {
        $this -> parseUserData($data);
        if ((isset($this -> data['user_name'])) && (isset($this -> data['password']))) {
            if (isset($this -> data['login'])) {
                if ($this -> checkUser($this -> data['user_name'], $this -> data['password'])) {
                    $this -> runApp();
                } else {
                    $this -> result = 'Пользователь не зарегистрирован';
                    $this -> defaultAction();
                }
            } elseif (isset($this -> data['register'])) {
                $this -> add($this -> data);
                $this -> result = 'Вы зарегистрированы';
                $this -> defaultAction();
            } else {
                $this -> defaultAction();
            }
        } else {
            $this -> result = 'Введите имя и пароль';
            $this -> defaultAction();
        }
    }
    
    public function getResult()
    {
        return $this -> result;
    }
    
    public function get_authorized_user()
    {
        return $_SESSION['user']['login'];
        //$pass = password_hash("admin", PASSWORD_DEFAULT);
    }
    
    private function parseUserData($data)
    {
        if (isset($data['user_name']) && preg_match('/[0-9A-z\s]+/', $data['user_name'])) {
            $this -> data['user_name'] = $data['user_name'];
        } else {
            $this -> errors['user_name'] = 'Error user name';
        }
        if (isset($data['user_password']) && preg_match('/[0-9A-z\s]+/', $data['user_password'])) {
            $this -> data['password'] = $data['user_password'];
        } else {
            $this -> errors['password'] = 'Error password';
        }
        if (isset($data['login'])) {
            $this -> data['login'] = $data['login'];
        } else {
            $this -> errors['login'] = 'Error login';
        }
        if (isset($data['register'])) {
            $this -> data['register'] = $data['register'];
        } else {
            $this -> errors['register'] = 'Error register';
        }
    }

    private function getUser($userName)
    { //функция получения пользователя по имени
        if (isset($userName)) {
            $userModel = new User();
            $user = $userModel -> getByName($userName);
var_dump($user);
            if (!empty($user)) {
                return $user;
            } else {
                return NULL;
            }
        }
    }

    private function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }
    
    private function isAdmin()
    {
        return ($_SESSION['user']['is_admin'] != 0);
    }

    private function checkUser($userName, $password)
    {
        $user = $this -> getUser($userName);
        if (!$user) {
            return FALSE;
        } else {
            if ($user['password'] === $password) {
                session_start();
                $_SESSION['user'] = $user;
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
    private function runApp()
    {
        if ($this -> isAuthorized()) {
            if ($this -> isAdmin()) {
                $questionController = new QuestionController(TRUE);
            } else {
                $questionController = new QuestionController(FALSE);
            }
            $questionController -> defaultAction();
        } else {
            $this -> result = 'Пользователь не авторизован';
        }
    }

}//end class Usercontroller