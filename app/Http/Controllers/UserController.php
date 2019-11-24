<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Model\User;
use App\Model\Volunteer;
use App\Model\Institution;
use App\Model\Interest;
use Illuminate\Support\Facades\Storage;
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
                400,
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
                400,
                $institution,
                'Validation error.',
                $validator->errors()
            );
        }

        $documentInvalid = Institution::validDocuments($institution);

        if ($documentInvalid) {
            return GeneralController::jsonReturn(
                false,
                400,
                $institution,
                'Validation error.',
                $documentInvalid
            );
        }

        $user['password'] = GeneralController::parseToSha256($user['password']);

        try {
            if (!$user = User::create($user)) {
                return GeneralController::jsonReturn(true, 400, $user, 'User not created.');
            }

            $institution['id_user'] = $user['id_user'];

            if (!Institution::create($institution)) {
                return GeneralController::jsonReturn(true, 400, $institution, 'Institution not created.');
            }

            $user['institution'] = $institution;

            return GeneralController::jsonReturn(true, 201, $user, 'Successfully created user institution.');
        } catch (Exception $exception)  {
            return GeneralController::jsonReturn(false, 400, null, 'Unexpected error.', $exception);
        }
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
                400,
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
                400,
                $volunteer,
                'Validation error.',
                $validator->errors()
            );
        }

        $documentInvalid = Volunteer::validDocuments($volunteer);

        if ($documentInvalid) {
            return GeneralController::jsonReturn(
                false,
                400,
                $volunteer,
                'Validation error.',
                $documentInvalid
            );
        }

        $user['password'] = GeneralController::parseToSha256($user['password']);

        try {
            if (!$user = User::create($user)) {
                return GeneralController::jsonReturn(true, 400, $user, 'User not created.');
            }

            $volunteer['id_user'] = $user['id_user'];

            if (!Volunteer::create($volunteer)) {
                return GeneralController::jsonReturn(true, 400, $volunteer, 'Volunteer not created.');
            }

            $user['volunteer'] = $volunteer;

            return GeneralController::jsonReturn(true, 201, $user, 'Successfully created user volunteer.');
        } catch (Exception $exception)  {
            return GeneralController::jsonReturn(false, 400, null, 'Unexpected error.', $exception);
        }
    }

    public function updatePassword(Request $request)
    {
        $req = $request->all();

        if ($req['new_password'] != $req['repeat_password']) {
            return GeneralController::jsonReturn(
                false,
                400,
                null,
                'Passwords do not match.',
                $errors['errors'] = [
                    'last_password' => "Password not match",
                    'repeat_password' => "Password not match",
                ]
            );
        }

        try {
            $user = User::where('id_user', $req['id_user'])->first();

            if (!$user) {
                return GeneralController::jsonReturn(false, 400, null, 'User not found.');
            }

            $lastPassword = GeneralController::parseToSha256($req['last_password']);
            $oldPassword = $user->password;

            if ($lastPassword != $oldPassword) {
                return GeneralController::jsonReturn(
                    false,
                    400,
                    null,
                    'Passwords do not match.',
                    $errors['errors'] = [
                        'last_password' => "Password not match",
                        'new_password' => "Password not match"
                    ]
                );
            }

            $newPassword = GeneralController::parseToSha256($req['new_password']);

            if (!$user->update(['password' => $newPassword])) {
                return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
            }

            return GeneralController::jsonReturn(true, 200, $user, 'Successfully updated user password.');
        } catch (Exception $exception)  {
            return GeneralController::jsonReturn(false, 400, null, 'Unexpected error.', $exception);
        }
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
                400,
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
                400,
                $volunteer,
                'Validation error.',
                $validator->errors()
            );
        }

        try {
            $userData = User::where('id_user', $user['id_user'])->first();

            $uniqueRules = User::uniqueRules($user, $userData);

            if ($uniqueRules) {
                return GeneralController::jsonReturn(
                    false,
                    400,
                    $user,
                    'Validation error.',
                    $uniqueRules
                );
            }

            $volunteerData = Volunteer::where('id_user', $user['id_user'])->first();

            $volunteer['id_user'] = $user['id_user'];
            $uniqueRules = Volunteer::uniqueRules($volunteer, $volunteerData);

            if ($uniqueRules) {
                return GeneralController::jsonReturn(
                    false,
                    400,
                    $volunteer,
                    'Validation error.',
                    $uniqueRules
                );
            }

            if (!$userData->update($user)) {
                return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
            }

            $volunteerData = Volunteer::where('id_volunteer', $volunteer['id_volunteer']);

            if (!$volunteerData->update($volunteer)) {
                return GeneralController::jsonReturn(false, 400, $volunteer, 'Volunteer not updated.');
            }

            $user['volunteer'] = $volunteer;

            return GeneralController::jsonReturn(true, 200, $user, 'Successfully updated user volunteer.');
        } catch (Exception $exception) {
            return GeneralController::jsonReturn(false, 400, $user, 'Unexpected Error.', $exception);
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
                400,
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
                400,
                $institution,
                'Validation error.',
                $validator->errors()
            );
        }

        try {
            $userData = User::where('id_user', $user['id_user'])->first();

            $uniqueRules = User::uniqueRules($user, $userData);

            if ($uniqueRules) {
                return GeneralController::jsonReturn(
                    false,
                    400,
                    $user,
                    'Validation error.',
                    $uniqueRules
                );
            }

            $institutionData = Institution::where('id_user', $user['id_user'])->first();

            $institution['id_user'] = $user['id_user'];
            $uniqueRules = Institution::uniqueRules($user, $institutionData);

            if ($uniqueRules) {
                return GeneralController::jsonReturn(
                    false,
                    400,
                    $institution,
                    'Validation error.',
                    $uniqueRules
                );
            }

            if (!$userData->update($user)) {
                return GeneralController::jsonReturn(false, 400, $user, 'User not updated.');
            }

            $institutionData = Institution::where('id_institution', $institution['id_institution']);

            if (!$institutionData->update($institution)) {
                return GeneralController::jsonReturn(false, 400, $institution, 'Institution not updated.');
            }

            $user['institution'] = $institution;

            return GeneralController::jsonReturn(true, 200, $user, 'Successfully updated user institution.');
        } catch (Exception $exception) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not updated.', $exception);
        }
    }

    public function findByToken(Request $request)
    {
        $req = $request->all();
        $token = $req['token'];

        if (!$user = User::where('token', $token)->first()) {
            return GeneralController::jsonReturn(false, 401, [],  'Token not found.');
        }

        return $this->getDataByUserId($user);
    }

    public function uploadImage(Request $request)
    {
        $req = $request->all();

        if (!$req['image']) {
            $req = $request->all();

            $errors['errors'] = [
                'image' => 'Not found'
            ];

            return GeneralController::jsonReturn(false, 400, null, 'Image not found.', $errors);
        }

        try {
            $user = User::where('id_user', $req['id_user'])->first();

            if (!$user) {
                $errors['errors'] = [
                    'user' => 'not found'
                ];

                return GeneralController::jsonReturn(false, 400, null, 'User not found.', $errors);
            }

            $filename = uniqid(date('HisYmd') . $user->id_user);

            $image = base64_decode($request['image']);

            $filename .= '.jpeg';

            $success = Storage::disk('public')->put($filename, $image);

            if (!$success) {
                $errors['errors'] = [
                    'upload' => 'Upload image error'
                ];

                return GeneralController::jsonReturn(false, 400, null, 'Upload image error.', $errors);
            }

            if (!$user->update(['image' => $filename])) {
                $errors['errors'] = [
                    'upload' => 'Insert image error'
                ];

                return GeneralController::jsonReturn(false, 400, null, 'Insert image error.', $errors);
            }

            return GeneralController::jsonReturn(true, 200, $user, 'Successfully updated user image.');
        } catch (Exception $exception) {
            return GeneralController::jsonReturn(false, 400, $user, 'User not updated.', $exception);
        }
    }
}
