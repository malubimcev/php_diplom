<?php
require_once 'autoload.php';
require_once 'controller/ControllerTraits.php';

class UserController extends Controller {
    
    use ParsingTrait;
    
    private $data = [];
    private $errors = [];
    private $result ='';
    private $authorizedUser = NULL;
    private $loginPage = 'login.twig';
    private $mainPage = 'questions.twig';
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
                    return TRUE;
                } else {
                    return FALSE;
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
        $user = new User();
        $user -> update($this -> data['id'], $this -> data);
        $this -> getList();
        $user = NULL;
    }

    public function getList()
    {
        if ($this -> isAdminMode()) {
            $user = new User();
            $this -> data = $user -> getList();
            if (!empty($this -> data)) {
                $view = new UserView($this -> userListPage);
                $view -> render($this -> data);
            }
        } else {
            $this -> defaultAction();
        }
    }

    public function getByName($name)
    {
        $user = new User();
        $this -> data = $user -> getByName($name);
        return $this -> data;
    }
   
    public function loginPage()
    {
        $view = new UserView($this -> loginPage);
        $this -> data['result'] = $this -> result;
        $view -> render($this -> data);
    }

    public function main()
    {
        $this -> runApp();
    }

    public function defaultAction()
    {
        $this -> main();
    }

    public function logout()
    {
        session_start();
        session_destroy();
        $this -> defaultAction();
        exit;
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
                    $this -> loginPage();
                }
            } elseif (isset($this -> data['register'])) {
                $this -> add($this -> data);
                $this -> result = 'Вы зарегистрированы';
                $this -> loginPage();
            } else {
                $this -> loginPage();
            }
        } else {
            $this -> result = 'Введите имя и пароль';
            $this -> loginPage();
        }
    }
    
    public function getUser($userData)
    {//функция ищет пользователя по имени или создает нового
        $user = $this -> getUserByName($userData['user_name']);
        if (!isset($user)) {
            $userModel = new User();
            $userData['password'] = '';
            $userData['is_admin'] = 0;
            if ($userModel -> add($userData)) {
                $user = $userModel -> getByName($userData['user_name']);
            } else {
                $user = NULL;
            }
        }
        return $user;
    }
    
    private function getResult()
    {
        return $this -> result;
    }
    
    private function getUserByName($userName)
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
        $user = $this -> getUserByName($userName);
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
        $questionController = new QuestionController();
        $questionController -> defaultAction();
        $questionController = NULL;
    }
    
    private function isAdminMode()
    {
        $app = Application::get();
        return $app -> isAdminMode();
    }
    
}//end class Usercontroller