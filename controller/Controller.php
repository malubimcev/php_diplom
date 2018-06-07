<?php

abstract class Controller
{
    abstract function add($params);
    abstract function update($params);
    abstract function delete($id);
    abstract function getList();
    abstract function defaultAction();
    
    public function redirect($page)
    {
	header("Location: $page");
	die;
    }
    
}//end class Controller

