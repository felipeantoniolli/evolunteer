<?php

namespace App\Http\Controllers;

use App\Model\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function insert(Request $request)
    {
        $interest = $request->all();

        if (!$interest = Interest::create($interest)) {
            return GeneralController::jsonReturn(true, 400, $interest, 'Interest not created.');
        }

        return GeneralController::jsonReturn(true, 201, $interest, 'Successfully created interest.');
    }
}
