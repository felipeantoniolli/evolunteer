<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Model\User;
use Validator;

class UserController extends Controller
{
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
            return GeneralController::jsonReturn(false, 400, $users, 'Users not found.');
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

    public function update(Request $request, User $user)
    {
        $req = $request->all();
        $rules = User::updateRules($req, $user);

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

        $user = $user->toArray();
        $req['password'] = GeneralController::parseToSha256($req['password']);

        foreach ($req as $index => $value) {
            if ($value != $user[$index]) {
                $user[$index] = $value;
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
