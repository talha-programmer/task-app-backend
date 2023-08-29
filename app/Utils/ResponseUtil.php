<?php

namespace App\Utils;

class ResponseUtil
{
    public static function getResponseArray($data, $status = false, $message = '', $errors = '')
    {
        if (!$data || (is_countable($data) && sizeof($data) == 0)) {
            $data = null;
        } 

        return [
            'status' => $status,
            'message' => $message,
            'validation_errors' => $errors,
            'data' => $data,
        ];
    }
}