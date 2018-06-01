<?php

require_once 'Model.php';

class Answer extends Model
{
    private $recordset = NULL;
    private $table_name = 'answers';
    
    public function add($data) 
    {
        if (!$this -> isExistRecord($data['id'], $this -> table_name)) {
            $request = 'INSERT INTO answers (
                            questions_id,
                            description,
                            status)
                        VALUES (
                            :category_id,
                            :description,
                            0)';
            $request_params = [
                ':category_id' => $data['category_id'],
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
                            answers
                        SET
                            category_id=:category_id,
                            description=:description,
                            status=:status
                        WHERE
                            id=:id';
            $params = [
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
        $fields = 'id AS id,
                    category_id,
                    description,
                    status,
                    date_added';
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
                    question_id,
                    description,
                    date_added';
        $this -> recordset = $this -> getRecord($id, $this -> table_name, $fields);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    public function getByQuestion($question_id)
    {
        $request = 'SELECT
                        answers.id AS id,
                        answers.description AS description,
                        answers.date_added AS date_added,
                    FROM
                        questions 
                    INNER JOIN
                        questions
                    ON
                        questions.id = answers.question_id
                    WHERE
                        question_id = :question_id
                    ORDER BY date_added DESC';
        $params = [
            ':question_id' => $question_id
        ];
        $this -> recordset = $this -> doRequest($request, $params);
        if (!isset($this -> recordset)) {
            return NULL;
        }
        return $this -> recordset;
    }

}//end class Answer model