<?php

require_once 'Model.php';

class User extends Model
{
    use NameTrait;
    
    private $recordset = NULL;
    private $table_name = 'users';
    private $users = [
        ['login' => 'admin', 'password' => 'admin']
    ];

    public function add($data) 
    {
        if (!$this -> isExistRecord($data['id'], $this -> table_name)) {
            $request = 'INSERT INTO users (
                            login,
                            password,
                            email.
                            is_admin)
                        VALUES (
                            :login,
                            :password,
                            :email,
                            :is_admin)';
            $request_params = [
                ':login' => $data['login'],
                ':password' => $data['password'],
                ':email' => $data['email'],
                ':is_admin' => $data['is_admin']
            ];
            $this -> doRequest($request, $request_params);
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
                            users
                        SET
                            login=:login,
                            password=:password,
                            email=:email,
                            is_admin=:is_admin
                        WHERE
                            id=:id';
            $params = [
                ':login' => $data['login'],
                ':password' => $data['password'],
                ':email' => $data['email'],
                ':is_admin' => $data['is_admin']
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
                   login,
                   password,
                   email,
                   is_admin';
        $this -> recordset = $this -> getAllRecords($this -> table_name, $fields, 'login', 'ASC');
        if (!empty($this -> recordset)) {
            return $this -> recordset;
        } else {
            return FALSE;
        }
    }
    
    public function getById($id)
    {
        $fields = 'id AS id,
                   login,
                   password,
                   email,
                   is_admin';
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
                   login,
                   password,
                   email,
                   is_admin';
        $this -> recordset = $this -> getRecordByName($name, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    public function getByRole($role)
    {
        $fields = 'id AS id,
                   login,
                   password,
                   email,
                   is_admin';
        $this -> recordset = $this -> getRecordByFieldValue($this -> table_name, $fields, 'is_admin', $role);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
}//end class User (Model)