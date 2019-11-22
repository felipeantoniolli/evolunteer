<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Model\User;
use App\Model\Volunteer;
use App\Model\Institution;
use App\Model\Interest;
use Exception;
use Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();

        $usernameOrEmail = $data['data'];

        $password = GeneralController::parseToSha256($data['password']);

        $user = User::where([
                ['email', $usernameOrEmail],
                ['password', $password]
            ])->orWhere([
                ['username', $usernameOrEmail],
                ['password', $password]
            ])->first();

        if (!$user) {
            return GeneralController::jsonReturn(false, 400, $user, 'Email or password fail.');
        }

        $user->token = GeneralController::generateToken($user->email, $user->password);
        $user->save();

        return $this->getDataByUserId($user);
    }

    public function getDataByUserId($user) {
        if ($user->type == 1) {
            $user->volunteer = Volunteer::where('id_user', $user->id_user)->first();
        } elseif ($user->type == 2) {
            $user->institution = Institution::where('id_user', $user->id_user)->first();
        }

        $user->interest = Interest::where('id_user', $user->id_user)->get();

        return GeneralController::jsonReturn(true, 200, $user, 'Connected.');
    }

    public function registerInstitution(Request $request)
    {
        $user = $request->all();
        $user = $user[0];

        $user['active'] = 1;
        $user['type'] = 2;

        $institution = $user['institution'];

        unset($user['volunteer']);
        unset($user['institution']);

        $rules = User::insertRules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $user,
                'Validation error.',
                $validator->errors()
            );
        }

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

        $user['password'] = GeneralController::parseToSha256($user['password']);

        if (!$user = User::create($user)) {
            return GeneralController::jsonReturn(true, 400, $user, 'User not created.');
        }

        $institution['id_user'] = $user['id_user'];

        if (!Institution::create($institution)) {
            return GeneralController::jsonReturn(true, 400, $institution, 'Institution not created.');
        }

        $user['institution'] = $institution;

        return GeneralController::jsonReturn(true, 201, $user, 'Successfully created user institution.');
    }

    public function registerVolunteer(Request $request)
    {
        $user = $request->all();
        $user = $user[0];

        $user['active'] = 1;
        $user['type'] = 1;

        $volunteer = $user['volunteer'];

        unset($user['volunteer']);
        unset($user['institution']);

        $rules = User::insertRules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $user,
                'Validation error.',
                $validator->errors()
            );
        }

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

        $user['password'] = GeneralController::parseToSha256($user['password']);

        if (!$user = User::create($user)) {
            return GeneralController::jsonReturn(true, 400, $user, 'User not created.');
        }

        $volunteer['id_user'] = $user['id_user'];

        if (!Volunteer::create($volunteer)) {
            return GeneralController::jsonReturn(true, 400, $volunteer, 'Volunteer not created.');
        }

        $user['volunteer'] = $volunteer;

        return GeneralController::jsonReturn(true, 201, $user, 'Successfully created user volunteer.');
    }

    public function updateVolunteer(Request $request)
    {
        $user = $request->all();
        $user = $user[0];

        $volunteer = $user['volunteer'];

        unset($user['password']);
        unset($user['volunteer']);
        unset($user['interest']);

        $rules = User::updateRules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $user,
                'Validation error.',
                $validator->errors()
            );
        }

        $rules = Volunteer::updateRules();

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

        try {
            $userData = User::where('id_user', $user['id_user'])->first();

            if (!$userData->update($user)) {
                return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
            }

            $volunteer['id_user'] = $user['id_user'];

            $volunteerData = Volunteer::where('id_volunteer', $volunteer['id_volunteer']);

            if (!$volunteerData->update($volunteer)) {
                return GeneralController::jsonReturn(false, 400, $volunteer, 'Volunteer not created.');
            }

            $user['volunteer'] = $volunteer;

            return GeneralController::jsonReturn(true, 201, $user, 'Successfully updated user volunteer.');
        } catch (Exception $exception) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not updated.', $exception);
        }
    }

    public function updateInstitution(Request $request)
    {
        $user = $request->all();
        $user = $user[0];

        $institution = $user['institution'];

        unset($user['password']);
        unset($user['institution']);
        unset($user['interest']);

        $rules = User::updateRules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $user,
                'Validation error.',
                $validator->errors()
            );
        }

        $rules = Institution::updateRules();

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

        try {
            $userData = User::where('id_user', $user['id_user'])->first();

            if (!$userData->update($user)) {
                return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
            }

            $institution['id_user'] = $user['id_user'];

            $institutionData = Institution::where('id_institution', $institution['id_institution']);

            if (!$institutionData->update($institution)) {
                return GeneralController::jsonReturn(false, 400, $institution, 'Institution not created.');
            }

            $user['institution'] = $institution;

            return GeneralController::jsonReturn(true, 201, $user, 'Successfully updated user institution.');
        } catch (Exception $exception) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not updated.', $exception);
        }
    }

    public function create(Request $request)
    {
        $user = $request->all();
        $rules = User::insertRules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $user,
                'Validation error.',
                $validator->errors()
            );
        }

        $user['password'] = GeneralController::parseToSha256($user['password']);

        if (!$user = User::create($user)) {
            return GeneralController::jsonReturn(true, 400, $user, 'User not created.');
        }

        return GeneralController::jsonReturn(true, 201, $user, 'Successfully created user.');
    }

    public function findAll()
    {
        $users = User::all();

        if (!$users) {
            return GeneralController::jsonReturn(false, 400, [], 'Users not found.');
        }

        return GeneralController::jsonReturn(true, 200, $users, 'Users successfully found.');
    }

    public function findById(User $user)
    {
        if (!$user) {
            return GeneralController::jsonReturn(false, 400, [],  'User not found.');
        }

        return GeneralController::jsonReturn(true, 200, $user, 'User successfully found.');
    }

    public function findByToken(Request $request)
    {
        $req = $request->all();
        $token = $req['token'];

        if (!$user = User::where('token', $token)->first()) {
            return GeneralController::jsonReturn(false, 400, [],  'Token not found.');
        }

        return $this->getDataByUserId($user);
    }

    public function update(Request $request, User $user)
    {
        $req = $request->all();
        $rules = User::updateRules();

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

        $uniqueRules = User::uniqueRules($req, $user);

        if ($uniqueRules) {
            return GeneralController::jsonReturn(
                false,
                401,
                $req,
                'Validation error.',
                $uniqueRules
            );
        }

        $req['password'] = GeneralController::parseToSha256($req['password']);

        foreach ($req as $index => $value) {
            if ($value != $user[$index]) {
                $user->$index = $value;
            }
        }

        if (!$user = $user->save()) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!$user->delete()) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $user, 'User successfully deleted');
    }
}
