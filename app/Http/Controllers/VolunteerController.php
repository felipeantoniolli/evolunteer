<?php

namespace App\Http\Controllers;

use App\Model\Volunteer;
use Illuminate\Http\Request;
use Validator;

class VolunteerController extends Controller
{
    public function create(Request $request)
    {
        $volunteer = $request->all();
        $rules = Volunteer::insertRules();

        $validator = Validator::make(
            $volunteer,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $volunteer,
                'Validation error.',
                $validator->errors()
            );
        }

        if (!Volunteer::create($volunteer)) {
            return GeneralController::jsonReturn(true, 400, $volunteer, 'Volunteer not created.');
        }

        return GeneralController::jsonReturn(true, 201, $volunteer, 'Successfully created volunteer.');
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $req = $request->all();
        $rules = Volunteer::Updaterules();

        $validator = Validator::make(
            $req,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $req,
                'Validation error.',
                $validator->errors()
            );
        }

        $uniqueRules = Volunteer::uniqueRules($req, $volunteer);

        if ($uniqueRules) {
            return GeneralController::jsonReturn(
                false,
                401,
                $req,
                'Validation error.',
                $uniqueRules
            );
        }

        foreach ($req as $index => $value) {
            if ($value != $volunteer[$index]) {
                $volunteer->$index = $value;
            }
        }

        if (!$volunteer = $volunteer->save()) {
            return GeneralController::jsonReturn(false, 400, $volunteer, 'Volunteer not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'Volunteer updated successfully.');
    }

    public function findAll()
    {
        $volunteers = Volunteer::all();

        if (!$volunteers) {
            return GeneralController::jsonReturn(false, 400, [], 'Volunteers not found.');
        }

        return GeneralController::jsonReturn(true, 200, $volunteers, 'Volunteers successfully found.');
    }

    public function findById(Volunteer $volunteer)
    {
        if (!$volunteer) {
            return GeneralController::jsonReturn(false, 400, [], 'Volunteer not found.');
        }

        return GeneralController::jsonReturn(true, 200, $volunteer, 'Volunteer successfully found.');
    }

    public function destroy(Volunteer $volunteer)
    {
        if (!$volunteer->delete()) {
            return GeneralController::jsonReturn(false, 400, $volunteer, 'Volunteer not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $volunteer, 'Volunteer successfully deleted');
    }
}
