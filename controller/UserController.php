<?php

require_once 'Controller.php';
require_once 'model/User.php';
require_once 'view/UserView.php';
//require_once 'view/UserView_1.php';

class UserController extends Controller {

    private $data = [];
    private $errors = [];
    private $result ='';

    public function add($params)
    {
        $user = new User();
        if (count($params) > 0) {
            $this -> data = $this -> parseUserData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $user -> add($this -> data);
                if ($idAdd) {
                    $this -> getList();
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
            }
        }
    }

    public function update($id, $params)
    {
        //
    }

    public function getList()
    {
        $user = new User();
        $this -> data = $user -> getList();
        if (!empty($this -> data)) {
            $view = new UserView();
            $view -> render($this -> data);
        }
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }

    public function logout()
    {
        session_destroy();
        $this -> getList();
    }

    public function login($data)
    {
        $this -> data = $this -> parseUserData($data);

//        if (!($this -> isAuthorized())) {
//            $this ->  result = 'Введите имя и пароль';
//            $this -> redirect('index.php');
//        } else {
//            $authorized_user = get_authorized_user();
//        }

        if ((isset($this -> data['user_name'])) && (isset($this -> data['user_password']))) {
            $login = $this -> data['user_name'];
            $password = $this -> data['user_password'];
            if (isset($this -> data['login'])) {
                if ($this -> checkUser($login, $password)) {
                    $taskController = new TaskController();
                    $taskController -> getList();
                } else {
                    $this -> result = 'Пользователь не зарегистрирован';
                }
            }
            if (isset($this -> data['register'])) {
                $this -> add($login, $password);
                $this -> result = 'Вы зарегистрированы';
            }
        } else {
            $this -> result = 'Введите имя и пароль';
        }
    }
    
    public function getResult()
    {
        return $this -> result;
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
            if (isset($user)) {
                return $user;
            } else {
                return NULL;
            }
        }
    }

    private function get_authorized_user()
    {
        return $_SESSION['user']['login'];
        //$pass = password_hash("admin", PASSWORD_DEFAULT);
    }

    private function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }
    
    private function checkUser($login, $password)
    {
        $user = $this -> getUser($login);
        if (!$user) {
            return FALSE;
        } else {
            if ($user['password'] === $password) {
                session_start();
                $_SESSION['user'] = $user;
                return TRUE;
            }
        }
        
    }

}//end class Usercontroller