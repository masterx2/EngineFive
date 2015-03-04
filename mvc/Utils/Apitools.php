<?php
/**
 * Created by PhpStorm.
 * User: masterx2
 * Date: 2/23/15
 * Time: 8:37 PM
 */

namespace App\Utils;

class Apitools {
    public static function error($code, $context) {
        $errors = [
            1001 => 'Api controller not requested',
            1002 => 'Api controller not found',
            1003 => 'Api wrong command',
            1004 => 'Api missparam',
            2001 => 'Password Incorrect',
            2002 => 'Login Incorrect',
            2003 => 'Login or Password empty',
            2004 => 'Must be filled all fields'
        ];

        self::sendJson([
            'error' => $code,
            'message' => $errors[$code],
            'context' => $context
        ]);
    }

    public static function sendJson($array) {
        header('Content-Type: application/json');
        return json_encode($array);
    }
}