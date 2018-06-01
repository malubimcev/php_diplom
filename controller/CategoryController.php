<?php

require_once 'Controller.php';
require_once 'model/Category.php';
require_once 'view/CategoryView.php';

class CategoryController extends Controller
{
    private $categories = [];//список вопросов
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    
    public function add($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> add($this -> data);
                $this -> getList();
            }
        }
    }
    
    public function delete($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> delete($this -> data);
                $this -> getList();
            }
        }
    }

    public function update($id, $params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $category -> update($this -> data);
                $this -> getList();
            }
        }
        
    }

    public function getList()
    {
        $category = new Category();
        $this -> categories = $task -> getList();
        if (!empty($this -> tasks)) {
            $view = new CategoryView();
            $view -> render($this -> tasks);
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
            $this -> errors['password'] = 'Error name';
        }
    }

}//end class CategoryController
