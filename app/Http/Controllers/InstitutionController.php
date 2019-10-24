<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Interest;
use App\Model\Institution;
use Illuminate\Http\Request;
use Validator;

class InstitutionController extends Controller
{
    public function create(Request $request)
    {
        $institution = $request->all();
        $rules = Institution::insertRules();

        $validator = Validator::make(
            $institution,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $institution,
                'Validation error.',
                $validator->errors()
            );
        }

        if (!Institution::create($institution)) {
            return GeneralController::jsonReturn(true, 400, $institution, 'Institution not created.');
        }

        return GeneralController::jsonReturn(true, 201, $institution, 'Successfully created Institution.');
    }

    public function getInstitutionByLocale(Request $request)
    {
        $req = $request->all();

        $city = $req['city'];
        $state = $req['state'];

        $user = User::where([
                ['city', $city],
                ['type', 2]
            ])->orWhere([
                ['state', $state],
                ['type', 2]
            ])->get();

        if (!$user) {
            return GeneralController::jsonReturn(true, 200, null, 'Successfully seach institutions');
        }

        $usersId[] = null;
        $users = [];
        foreach ($user as $one) {
            $users[$one->id_user] = $one;
            $usersId[] = $one->id_user;
        }

        $institutions = Institution::whereIn('id_user', $usersId)->get();
        $interests = Interest::whereIn('id_user', $usersId)->get();

        $json = [];
        foreach ($institutions as $one) {
            $json[$one->id_user] = [
                'user' => $users[$one->id_user],
                'institution' => $one
            ];
        }

        foreach ($interests as $one) {
           $json[$one->id_user]['interest'] = $one;
        }

        return GeneralController::jsonReturn(true, 200, $json, 'Successfully seach institutions');
    }

    public function update(Request $request, Institution $institution)
    {
        $req = $request->all();
        $rules = Institution::Updaterules();

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

        $uniqueRules = Institution::uniqueRules($req, $institution);

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
            if ($value != $institution[$index]) {
               $institution->$index = $value;
            }
        }

        if (!$institution = $institution->save()) {
            return GeneralController::jsonReturn(false, 400, $institution, 'Institution not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'Institution updated successfully.');
    }

    public function findAll()
    {
        $institutions = Institution::all();

        if (!$institutions) {
            return GeneralController::jsonReturn(false, 400, [], 'Institutions not found.');
        }

        return GeneralController::jsonReturn(true, 200, $institutions, 'Institutions successfully found.');
    }

    public function findById(Institution $institution)
    {
         if (!$institution) {
            return GeneralController::jsonReturn(false, 400, [],  'Institution not found.');
        }

        return GeneralController::jsonReturn(true, 200, $institution, 'Institution successfully found.');
    }

    public function destroy(Institution $institution)
    {
         if (!$institution->delete()) {
            return GeneralController::jsonReturn(false, 400, $institution, 'Institution not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $institution, 'Institution successfully deleted');
    }
}
