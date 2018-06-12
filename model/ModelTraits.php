<?php
    require_once 'Database.php';

    trait DatabaseTrait
    {
        private $db = NULL;
        
        public function doRequest($request, $params)
        {
           $this -> get_database(); 
           return $this -> db -> do_request($request, $params);
        }

        private function get_database()
        {
            if ($this -> db === NULL) {
                $this -> db = new Database();
            }
        }

    }//end trait DatabaseTrait

    trait RecordsTrait
    {
        use DatabaseTrait;
        
        public function isExistRecord($id, $table)
        {
            $fields = 'id AS id ';
            $record = $this -> getRecord($id, $table, $fields);
            if (!empty($record)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function deleteRecord($id, $table)
        {
            if ($this -> isExistRecord($id, $table)) {
                $request = 'DELETE FROM ';
                $request .=  $table;
                $request .=' WHERE id = :id';
                $request_params = [':id' => $id];
                $this -> doRequest($request, $request_params);
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        public function getAllRecords($table, $fields, $sort_field = 'id', $sort_order = "ASC")
        {
            $record = [];
            if (empty($fields)) {
                $fields = '*';
            }
            $request = 'SELECT ';
            $request .= $fields;
            $request .= ' FROM ';
            $request .=  $table;
            $request .=' ORDER BY ';
            $request .= $sort_field;
            $request .= ' '.$sort_order;
            $request_params = [':id' => $id];
            $record = $this -> doRequest($request, $request_params);
            if (!empty($record)) {
                return $record;
            } else {
                return FALSE;
            }
        }
        
        public function getRecordByFieldValue($table, $fields, $target_field = 'id', $value = '')
        {
            $record = [];
            if (empty($fields)) {
                $fields = '*';
            }
            $request = 'SELECT ';
            $request .= $fields;
            $request .= ' FROM ';
            $request .=  $table;
            $request .=' WHERE ';
            $request .= $target_field .'=:'.$value;
            $request_params = [':'.$value => $value];
            $record = $this -> doRequest($request, $request_params);
            if (!empty($record)) {
                return $record;
            } else {
                return FALSE;
            }
        }
        
        public function getRecord($id, $table, $fields)
        {
            $record = $this -> getRecordByFieldValue($table, $fields, 'id', $id);
            return $record;
        }
        
    }//end trait RecordsTrait
    
    trait NameTrait
    {
        use DatabaseTrait;
        
        public function getRecordByName($name, $table, $fields)
        {
            $record = $this -> getRecordByFieldValue($table, $fields, 'name', $name);
            return $record;
        }
                
    }//end trait NameTrait