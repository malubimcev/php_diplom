<?php
require_once 'autoload.php';
require_once 'controller/ControllerTraits.php';

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
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                $category -> add($this -> data);
                $this -> getList();
            }
        }
        $category = NULL;
    }
    
    public function delete($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                $category -> delete($this -> data['id']);
                $this -> getList();
            }
        }
        $category = NULL;
    }

    public function update($params)
    {
        $category = new Category();
        if (count($params) > 0) {
            $this -> parseData($params, $this -> data);
            if (count($this -> errors) == 0) {
                $category -> update($this -> data['id'], $this -> data);
                $this -> getList();
            }
        }
        $category = NULL;
    }

    public function getList()
    {
        $category = new Category();
        $this -> categories = $category -> getList();
        $this -> setTemplate();
        $view = new CategoryView($this -> viewTemplate);
        $view -> render($this -> categories);
        $category = NULL;
    }
    
    public function defaultAction()
    {
        $this -> getList();
    }
        
    private function setTemplate()
    {
        $app = Application::get();
echo 'app=== '.(string)($app->isAdminMode()).' ===<br>';
        if ($app -> isAdminMode()) {
            $this -> viewTemplate = 'categoryList.twig';
        } else {
            $this -> viewTemplate = 'categories.twig';
        }
    }

}//end class CategoryController
