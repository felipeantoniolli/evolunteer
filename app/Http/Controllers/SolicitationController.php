<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Solicitation;
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
            ['id_institution', $req['id_institution']]
        ])->first();

        if (!$solicitation) {
            return GeneralController::jsonReturn(true, 200, null, 'The user has no solicitations from this institution.');
        }

        return GeneralController::jsonReturn(true, 200, $solicitation, 'Solicitation find successfully.');
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

