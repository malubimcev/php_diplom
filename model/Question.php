<?php

require_once 'Model.php';

class Question extends Model
{
    private $recordset = NULL;
    private $table_name = 'questions';

private $testQuestions = [
    [
        'id' => 1,
        'description' => 'quest1',
        'category_id' => '1',
        'user_id' => '2',
        'status' => '0'
    ],
    [
        'id' => 2,
        'description' => 'quest2',
        'category_id' => '2',
        'user_id' => '1',
        'status' => '1'
    ],
    [
        'id' => 3,
        'description' => 'quest3',
        'category_id' => '1',
        'user_id' => '1',
        'status' => '1'
    ]
];
    
    public function add($data) 
    {
        if (!$this -> isExistRecord($data['id'], $this -> table_name)) {
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
                            questions
                        SET
                            user_id=:user_id,
                            category_id=:category_id,
                            description=:description,
                            status=:status
                        WHERE
                            id=:id';
            $params = [
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
    
    public function getList()
    {
return $this->testQuestions;//=============================================================
        $fields = 'id AS id,
                    category_id,
                    user_id,
                    description,
                    status';
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
                    category_id,
                    user_id,
                    description,
                    status';
        $this -> recordset = $this -> getRecord($id, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    public function getByUser($user_name)
    {
        $category = new Category();
        $user_id = $this -> get_user_id($user_name);
        $request = 'SELECT
                        questions.id AS id,
                        questions.description AS description,
                        questions.date_added AS date_added,
                        questions.status AS status,
                        users.login AS user_name,
                        category.name AS category_name
                    FROM
                        questions 
                    INNER JOIN
                        users
                    ON
                        users.id = questions.user_id
                    INNER JOIN
                        categories
                    ON
                        categories.id = questions.category_id
                    WHERE
                        AND users.login = :user_name
                    ORDER BY users.login ASC';
        $params = [
            ':user_name' => $user_name
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            return NULL;
        }
        return $this -> recordset;
    }

    public function getByCategory($category_name)
    {
        $category_id = $this -> get_user_id($category_name);
        $request = 'SELECT
                        questions.id AS id,
                        questions.description AS description,
                        questions.date_added AS date_added,
                        questions.status AS status,
                        user.login AS user_name,
                        category.name AS category_name
                    FROM
                        questions 
                    INNER JOIN
                        user
                    ON
                        user.id = questions.user_id
                    INNER JOIN
                        categories
                    ON
                        categories.id = questions.category_id
                    WHERE
                        categories.name = :category_name
                    ORDER BY categories.name ASC';
        $params = [
            ':category_name' => $category_name
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            return NULL;
        }
        return $this -> recordset;
    }

}//end class UserModel