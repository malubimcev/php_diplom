<?php

require_once 'autoload.php';

class Category extends Model
{
    use NameTrait;
    
    private $category = NULL;
    private $table_name = 'categories';
    
    public function add($data) 
    {
        $request = 'INSERT INTO categories (
                        name)
                    VALUES (
                        :name)';
        $params = [
            ':name' => $data['name'],
        ];
        $this -> doRequest($request, $params);
        return TRUE;
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
            $params = [
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
        $fields = 'id AS id,
                   name';
        $this -> recordset = $this -> getAllRecords($this -> table_name, $fields, 'name', 'ASC');
        if (!empty($this -> recordset)) {
            return $this -> recordset;
        } else {
            return FALSE;
        }
    }
    
    public function getGroupped()
    {
        $question = new Question();
        $this -> recordset = $question -> getGroupped();
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