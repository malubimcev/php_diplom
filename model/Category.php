<?php

require_once 'Model.php';

class Category extends Model
{
    use NameTrait;
    
    private $category = NULL;
    private $table_name = 'categories';
    
private $testCategories = [
    ['id' => 1,
     'name' => 'cat1'],
    ['id' => 2,
     'name' => 'cat2'],
    ['id' => 3,
     'name' => 'cat3']        
];
    
    public function add($data) 
    {
        if ($this -> isExistRecord($data['id'], $this -> table_name)) {
            $request = 'INSERT INTO categories (
                            name
                        VALUES (
                            :name';
            $params = [
                ':name' => $data['name'],
            ];
            $this -> doRequest($request, $params);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delete($id) 
    {
        return $this -> deleteRecord($id, $this -> table_name);
    }
    
    public function update($id, $data) 
    {
        if ($this -> isExistRecord($id, $this -> table_name)) {
            $request = 'UPDATE
                            categories
                        SET
                            name=:name
                        WHERE
                            id=:id';
            $request_params = [
                ':name' => $data['name'],
                ':id' => $data['id']
            ];
            $this -> doRequest($request, $params);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getList()
    {
return $this->testCategories;//============================        
        $fields = 'id AS id,
                   name';
        $this -> recordset = $this -> getAllRecords($this -> table_name, $fields, 'name', 'ASC');
        if (!empty($this -> recordset)) {
            return $this -> recordset;
        } else {
            return FALSE;
        }
    }
    
    public function getById($id)
    {
        $fields = 'id AS id,
                   name';
        $this -> recordset = $this -> getRecord($id, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    public function getByName($name)
    {
        $fields = 'id AS id,
                   name';
        $this -> recordset = $this -> getRecordByName($name, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
}//end class CategoryModel