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
}
