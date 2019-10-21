<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public static function parseToSha256($data)
    {
        $secret = 'T3Gda6eiFBNu6KrFhoa2fHcicrP4xh';
        return hash('sha256', $data . $secret);
    }

    public static function generateToken($email, $password)
    {
        $secret = 'T3Gda6eiFBNu6KrFhoa2fHcicrP4xh';
        $date = date('YmdHis');

        $token = hash('sha256', $date. $secret . $email . $password);

        return $token;
    }

    public static function jsonReturn(
        $success = false,
        $response,
        $data = [],
        $message = "",
        $errors = []
    ) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ], $response);
    }
}
