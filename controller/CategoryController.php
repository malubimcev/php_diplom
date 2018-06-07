<?php

require_once 'Controller.php';
require_once 'controller/Application.php';
require_once 'controller/ControllerTraits.php';
require_once 'model/Category.php';
require_once 'view/CategoryView.php';

class CategoryController extends Controller
{
    use ParsingTrait;
    
    private $categories = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    private $viewTemplate = 'categories.twig';
    
    public function add($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> add($this -> data);
                $this -> getList();
            }
        }
        $category = NULL;
    }
    
    public function delete($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> delete($this -> data);
                $this -> getList();
            }
        }
        $category = NULL;
    }

    public function update($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> update($this -> data);
                $this -> getList();
            }
        }
        $category = NULL;
    }

    public function getList()
    {
        $category = new Category();
        $this -> categories = $category -> getList();
        if (!empty($this -> categories)) {
            $this -> setTemplate();
            $view = new CategoryView($this -> viewTemplate);
            $view -> render($this -> categories);
        }
        $category = NULL;
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
        
    private function parseData($data)
    {
        if (isset($data['id']) && preg_match('/[0-9\s]+/', $data['id'])) {
            $this -> data['id'] = $data['id'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
        if (isset($data['name']) && preg_match('/[0-9A-z\s]+/', $data['name'])) {
            $this -> data['name'] = $data['name'];
        } else {
            $this -> errors['name'] = 'Error name';
        }
        if (isset($data['paramName']) && ($data['paramName'] == 'id')) {
            $this -> data['id'] = $data['paramValue'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
    }
    
    private function setTemplate()
    {
        $app = Application::get();
        if ($app -> isAdminMode()) {
            $this -> viewTemplate = 'categoriesAdmin.twig';
        } else {
            $this -> viewTemplate = 'categories.twig';
        }
    }

}//end class CategoryController
