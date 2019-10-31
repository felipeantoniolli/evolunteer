<?php

namespace App\Http\Controllers;

use App\Model\Interest;
use App\Model\Solicitation;
use App\Model\User;
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

    public function generateVolunteersListBySolicitation($solicitations) {
        $volunteerIds = [];
        $dataSolicitations = [];

        foreach ($solicitations as $one) {
            $volunteerIds[$one->id_volunteer] = $one->id_volunteer;
            $dataSolicitations[$one->id_volunteer] = $one;
        }

        $volunteers = Volunteer::whereIn('id_volunteer', $volunteerIds)->get();

        $userIds = [];
        $dataVolunteers = [];
        foreach ($volunteers as $one) {
            $userIds[$one->id_volunteer] = $one->id_user;
            $dataVolunteers[$one->id_user] = $one;
        }

        $users = User::whereIn('id_user', $userIds)->get();

        $interests = Interest::whereIn('id_user', $userIds)->get();

        $json = [];
        foreach ($users as $one) {
            $volunteer = $dataVolunteers[$one->id_user];
            $solicitation = $dataSolicitations[$volunteer->id_volunteer];
            $one->volunteer = $volunteer;
            $one->solicitation = $solicitation;
            $interest = [];

            foreach ($interests as $item) {
                if ($item->id_user == $one->id_user) {
                    $interest[] = $item;
                }
            }

            $one->interest = $interest;
            $json[] = [
                'user' => $one,
            ];
        }

        return $json;
    }

    public function getVolunteersBySolicitationPending(Request $request)
    {
        $req = $request->all();

        $solicitations = Solicitation::where('id_institution', $req['id_institution'])
            ->where('approved', 0)->get();

        if (!$solicitations) {
            return GeneralController::jsonReturn(false, 400, [], 'Solicitations not found.');
        }

        $json = $this->generateVolunteersListBySolicitation($solicitations);

        return GeneralController::jsonReturn(true, 200, $json, 'Solicitations successfully found.');
    }

    public function getVolunteersBySolicitationApproved(Request $request)
    {
        $req = $request->all();

        $solicitations = Solicitation::where('id_institution', $req['id_institution'])
            ->where('approved', 1)->get();

        if (!$solicitations) {
            return GeneralController::jsonReturn(false, 400, [], 'Solicitations not found.');
        }

        $json = $this->generateVolunteersListBySolicitation($solicitations);

        return GeneralController::jsonReturn(true, 200, $json, 'Solicitations successfully found.');
    }
}
