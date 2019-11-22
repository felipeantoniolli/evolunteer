<?php

namespace App\Http\Controllers;

use App\Model\Institution;
use App\Model\User;
use App\Model\Interest;
use App\Model\Solicitation;
use Illuminate\Http\Request;
use Validator;

class SolicitationController extends Controller
{
    public function create(Request $request)
    {
        $solicitation = $request->all();
        $rules = Solicitation::rules();

        $validator = Validator::make(
            $solicitation,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $solicitation,
                'Validation error.',
                $validator->errors()
            );
        }

        if (!Solicitation::create($solicitation)) {
            return GeneralController::jsonReturn(true, 400, $solicitation, 'Solicitation not created.');
        }

        return GeneralController::jsonReturn(true, 201, $solicitation, 'Successfully created Solicitation.');
    }

    public function update(Request $request, Solicitation $solicitation)
    {
        $req = $request->all();
        $rules = Solicitation::rules();

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

        foreach ($req as $index => $value) {
            if ($value != $solicitation[$index]) {
                $solicitation->$index = $value;
            }
        }

        if (!$solicitation = $solicitation->save()) {
            return GeneralController::jsonReturn(false, 400, $solicitation, 'Solicitation not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'Solicitation updated successfully.');
    }

    public function findSolicitationPendingByUserAndInstitution(Request $request) {
        $req = $request->all();

        $solicitation = Solicitation::where([
            ['id_volunteer', $req['id_volunteer']],
            ['id_institution', $req['id_institution']],
            ['approved', '<>', 3]
        ])->first();

        if (!$solicitation) {
            return GeneralController::jsonReturn(true, 200, null, 'The user has no solicitations from this institution.');
        }

        return GeneralController::jsonReturn(true, 200, $solicitation, 'Solicitation find successfully.');
    }

    public function findByVolunteer(Request $request)
    {
        $req = $request->all();

        $solicitations = Solicitation::where([
            ['id_volunteer', $req['id_volunteer']],
            ['approved', '<>', 3]
        ])->get();

        if (!$solicitations) {
            return GeneralController::jsonReturn(true, 200, null, 'The user has no solicitations from this institution.');
        }

        $institutionsId = [];
        foreach ($solicitations as $one) {
            $institutionsId[$one->id_institution] = $one->id_institution;
        }

        $institutions = Institution::whereIn('id_institution', $institutionsId)->get();

        if (!$institutions) {
            return GeneralController::jsonReturn(true, 200, null, 'The user has no solicitations from this institution.');
        }

        $usersId = [];
        $institutionsData = [];
        foreach ($institutions as $one) {
            $usersId[$one->id_user] = $one->id_user;
            $institutionsData[$one->id_user] = $one;
        }

        $users = User::whereIn('id_user', $usersId)->get();
        $interests = Interest::whereIn('id_user', $usersId)->get();

        if (!$users) {
            return GeneralController::jsonReturn(true, 200, null, 'The user has no solicitations from this institution.');
        }

        foreach ($users as $one) {
            $one->institution = $institutionsData[$one->id_user];

            $interest = [];
            foreach ($interests as $item) {
                if ($item->id_user == $one->id_user) {
                    $interest[] = $item;
                }
            }

            $one->interest = $interest;
            $json[] = [
                'user' => $one
            ];
        }

        return GeneralController::jsonReturn(true, 200, $json, 'Successfully find solicitations');
    }

    public function findAll()
    {
        $solicitations = Solicitation::all();

        if (!$solicitations) {
            return GeneralController::jsonReturn(false, 400, [], 'Solicitations not found.');
        }

        return GeneralController::jsonReturn(true, 200, $solicitations, 'Solicitations successfully found.');
    }

    public function findById(Solicitation $solicitation)
    {
        if (!$solicitation) {
            return GeneralController::jsonReturn(false, 400, [], 'Solicitation not found.');
        }

        return GeneralController::jsonReturn(true, 200, $solicitation, 'Solicitation successfully found.');
    }

    public function destroy(Solicitation $solicitation)
    {
        if (!$solicitation->delete()) {
            return GeneralController::jsonReturn(false, 400, $solicitation, 'Solicitation not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $solicitation, 'Solicitation successfully deleted');
    }

    public function updateStatusSolicitation(Request $request)
    {
        $req = $request->all();

        $solicitation = Solicitation::where('id_solicitation', $req['id_solicitation'])->first();

        if (!$solicitation) {
            return GeneralController::jsonReturn(false, 400, [], 'Solicitation not found.');
        }

        $solicitation->approved = $req['approved'];

        if (!$solicitation = $solicitation->save()) {
            return GeneralController::jsonReturn(false, 400, [], 'Error updating solicitation.');
        }

        return GeneralController::jsonReturn(true, 200, $solicitation, 'Solicitations successfully updated.');
    }
}

