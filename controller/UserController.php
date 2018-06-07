<?php
require_once 'Controller.php';
require_once 'model/User.php';
require_once 'controller/Application.php';
require_once 'controller/ControllerTraits.php';
require_once 'view/UserView.php';

class UserController extends Controller {
    
    use ParsingTrait;
    
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
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                if ($user -> add($this -> data)) {
                    $this -> getList();
                    $user = NULL;
                }
            }
        }
    }

    public function delete($params)
    {
        $this -> parseData($params, $this -> data);
        $user = new User();
        $isDelete = $user -> delete($this -> data['id']);
        if ($isDelete) {
            $this -> getList();
            $user = NULL;
        } else {
            $errors[] = 'User delete error';
        }
    }

    public function update($params)
    {
        $this -> parseData($params, $this -> data);
        if (empty($this -> errors)) {
            $user = new User();
            $user -> update($this -> data['id'], $this -> data);
            $this -> getList();
            $user = NULL;
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
        $this -> parseData($data, $this -> data);
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
    
    private function getUser($userName)
    { //функция получения пользователя по имени
        if (isset($userName)) {
            $userModel = new User();
            $user = $userModel -> getByName($userName);
            if (!empty($user)) {
                return $user;
            } else {
                return NULL;
            }
        }
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
        $app = Application::get();
        $app -> run();
    }
    
    private function setTemplate()
    {
        $app = Application::get();
        if ($app -> isAdminMode()) {
            $this -> viewTemplate = 'userList.twig';
        } else {
            $this -> viewTemplate = 'login.twig';
        }
    }
    
}//end class Usercontroller