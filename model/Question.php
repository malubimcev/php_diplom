<?php

require_once 'autoload.php';

class Question extends Model
{
    private $recordset = NULL;
    private $table_name = 'questions';

    public function add($data) 
    {
        $request = 'INSERT INTO questions (
                        category_id,
                        user_id,
                        description,
                        status)
                    VALUES (
                        :category_id,
                        :user_id,
                        :description,
                        0)';
        $request_params = [
            ':category_id' => $data['category_id'],
            ':user_id' => $data['user_id'],
            ':description' => $data['description']
        ];
        $this -> doRequest($request, $request_params);
    }
    
    public function delete($id) 
    {
        return $this -> deleteRecord($id, $this -> table_name);
    }
    
    public function update($id, $data)
    {
        if ($this -> isExistRecord($id, $this -> table_name)) {
            $request = 'UPDATE
                            questions
                        SET
                            user_id=:user_id,
                            category_id=:category_id,
                            description=:description,
                            status=:status
                        WHERE
                            id=:id';
            $params = [
                ':id' => $id,
                ':user_id' => $data['user_id'],
                ':category_id' => $data['category_id'],
                ':description' => $data['description'],
                ':status' => $data['status']
            ];
            $this -> doRequest($request, $params);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateStatus($id, $status)
    {
        if ($this -> isExistRecord($id, $this -> table_name)) {
            $request = 'UPDATE
                            questions
                        SET
                            status=:status
                        WHERE
                            id=:id';
            $params = [
                ':id' => $id,
                ':status' => $status
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
                   category_id,
                   user_id,
                   description,
                   status';
        $this -> recordset = $this -> getAllRecords($this -> table_name, $fields, 'login', 'ASC');
        return $this -> recordset;
    }
    
    public function getById($id)
    {
        $fields = 'id AS id,
                    category_id,
                    user_id,
                    description,
                    status';
        $this -> recordset = $this -> getRecord($id, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset;
        }
    }
    
    public function getByCategory($category_id)
    {
        $request = 'SELECT
                        questions.id AS id,
                        questions.description AS description,
                        questions.date_added AS date_added,
                        questions.status AS status,
                        questions.category_id,
                        questions.user_id,
                        users.login AS user_name
                    FROM
                        questions 
                    INNER JOIN
                        users
                    ON
                        users.id=questions.user_id
                    WHERE
                        category_id=:category_id
                    AND
                        questions.status=1
                    ORDER BY questions.date_added DESC';
        $params = [
            ':category_id' => $category_id
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            echo 'questions not selected';
            return NULL;
        }
        return $this -> recordset;
    }
    
    public function getAllByCategory($category_id)
    {
        $request = 'SELECT
                        questions.id AS id,
                        questions.description AS description,
                        questions.date_added AS date_added,
                        questions.status AS status,
                        questions.category_id,
                        questions.user_id,
                        users.login AS user_name
                    FROM
                        questions 
                    INNER JOIN
                        users
                    ON
                        users.id=questions.user_id
                    WHERE
                        category_id=:category_id
                    ORDER BY questions.date_added DESC';
        $params = [
            ':category_id' => $category_id
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            echo 'questions not selected';
            return NULL;
        }
        return $this -> recordset;
    }
    
    public function getUnanswered()
    {
        $request = 'SELECT
                        questions.id AS id,
                        questions.description AS description,
                        questions.date_added AS date_added,
                        questions.status AS status,
                        questions.category_id,
                        questions.user_id,
                        users.login AS user_name,
                        categories.name AS category_name
                    FROM
                        questions 
                    INNER JOIN
                        users
                    ON
                        users.id=questions.user_id
                    INNER JOIN
                        categories
                    ON
                        categories.id=questions.category_id
                    WHERE
                        questions.status=0
                    ORDER BY questions.date_added DESC';
        $params = [];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            echo 'questions not selected';
            return NULL;
        }
        return $this -> recordset;
    }

    public function getGroupped()
    {
        $request = 'SELECT
                        categories.name,
                        categories.id,
                        COUNT(questions.status) AS total_count
                    FROM
                        questions 
                    INNER JOIN
                        categories
                    ON
                        categories.id=questions.category_id
                    GROUP BY
                        categories.name';
        $params = [
            //':category_id' => $category_id
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            return NULL;
        }
        return $this -> recordset;
    }
    
    public function deleteByCategory($category_id)
    {
        $request = 'DELETE FROM
                        questions 
                    WHERE
                        category_id=:category_id';
        $params = [
            ':category_id' => $category_id
        ];
        $this -> doRequest($request, $params);
        return;
    }

}//end class UserModel