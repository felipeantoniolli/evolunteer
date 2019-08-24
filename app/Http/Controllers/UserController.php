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
        $rules = User::rules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'user' => $user
            ], 401);
        }

        $user['password'] = GeneralController::parseToSha256($user['password']);
        if (!$user = User::create($user)) {
            return response()->json([
                'success' => false,
                'errors' => 'Users not created',
                'user' => $user
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created user',
            'user' => $user
        ], 201);
    }

    public function findAll()
    {
        return response()->json([
            'success' => true,
            'message' => 'Users successfully found',
            'users' => User::all()
        ], 200);
    }

    public function findById(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'User successfully found',
            'user' => $user
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $req = $request->all();
        $req['password'] = GeneralController::parseToSha256($req['password']);

        foreach ($req as $index => $value) {
            if ($value != $user[$index]) {
                $user[$index] = $value;
            }
        }

        $rules = User::rules();

        $validator = Validator::make(
            $user,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'user' => $user
            ], 401);
        }

        if (!$user = $user->save()) {
            return response()->json([
                'success' => false,
                'user' => $user
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }

    public function destroy(User $user)
    {
        if (!$user->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'User successfully deleted'
        ], 200);
    }
}
