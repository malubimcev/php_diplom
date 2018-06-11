<?php

trait ParsingTrait
{
    public function parseData(&$inputData, &$outputData)
    {
        if (isset($inputData['paramName'])) {
            $outputData[$inputData['paramName']] = $inputData['paramValue'];
        }
        if (isset($inputData['id']) && preg_match('/[0-9\s]+/', $inputData['id'])) {
            $outputData['id'] = $inputData['id'];
        }
        if (isset($inputData['user_id']) && preg_match('/[0-9\s]+/', $inputData['user_id'])) {
            $outputData['user_id'] = $inputData['user_id'];
        }
        if (isset($inputData['question_id']) && preg_match('/[0-9\s]+/', $inputData['question_id'])) {
            $outputData['question_id'] = $inputData['question_id'];
        }
        if (isset($inputData['category_id']) && preg_match('/[0-9\s]+/', $inputData['category_id'])) {
            $outputData['category_id'] = $inputData['category_id'];
        }
        if (isset($inputData['description'])) {
            $outputData['description'] = $inputData['description'];
        }
        if (isset($inputData['name']) && preg_match('/[0-9A-zА-я\s]+/', $inputData['name'])) {
            $outputData['name'] = $inputData['name'];
        }
        if (isset($inputData['user_name']) && preg_match('/[0-9A-z\s]+/', $inputData['user_name'])) {
            $outputData['user_name'] = $inputData['user_name'];
        }
        if (isset($inputData['user_password']) && preg_match('/[0-9A-z\s]+/', $inputData['user_password'])) {
            $outputData['password'] = $inputData['user_password'];
        }
        if (isset($inputData['login'])) {
            $outputData['login'] = $inputData['login'];
        }
        if (isset($inputData['register'])) {
            $outputData['register'] = $inputData['register'];
        }
        if (isset($inputData['email']) && preg_match('/[0-9A-z\@\.\s]+/', $inputData['email'])) {
            $outputData['email'] = $inputData['email'];
        }
        if (isset($inputData['is_admin'])) {
            if ($inputData['is_admin'] == (TRUE || 'on' || '1')) {
                $outputData['is_admin'] = 1;
            } else {
                $outputData['is_admin'] = 0;
            }
        }
        if (isset($inputData['status']) && preg_match('/[0-3\s]+/', $inputData['status'])) {
            $outputData['status'] = $inputData['status'];
        }
    }

}//end trait ParsingTrait

