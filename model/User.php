<?php
require_once 'autoload.php';

class User extends Model
{
    private $recordset = NULL;
    private $table_name = 'users';

    public function add($data) 
    {
        $this -> validate($data);
        if (!($this -> isExistRecord($data['id'], $this -> table_name))) {
            $request = 'INSERT INTO users (
                            login,
                            password,
                            email,
                            is_admin)
                        VALUES (
                            :login,
                            :password,
                            :email,
                            :is_admin)';
            $request_params = [
                ':login' => $data['user_name'],
                ':password' => $data['password'],
                ':email' => $data['email'],
                ':is_admin' => $data['is_admin']
            ];
            try {
                $this -> doRequest($request, $request_params);
                return TRUE;
            } catch (Exception $ex) {
                echo 'Ошибка добавления пользователя: '.$ex -> getMessage()."\n";
                return FALSE;
            }
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
        $this -> data = $this ->validate($data);
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
                ':id' => $data['id'],
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
        $this -> recordset = $this -> getRecordByFieldValue($this -> table_name, $fields,'login',$name);
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
    
    private function validate($data)
    {
        if (!isset($data['email'])) {
            $data['email'] = ' ';
        }
    }
    
}//end class User (Model)