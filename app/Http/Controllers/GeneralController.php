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

    public function validCpf($cpf = null)
    {
        if(!$cpf) {
            return false;
        }

        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        if (strlen($cpf) != 11) {
            return false;
        }

        else if (
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return false;
         } else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }


    public static function validCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
            $sum += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;
        if ($cnpj{12} != ($rest < 2 ? 0 : 11 - $rest)) {
            return false;
        }

        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
            $sum += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;

        return $cnpj{13} == ($rest < 2 ? 0 : 11 - $rest);
    }
}
