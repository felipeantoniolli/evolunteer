<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Model\User;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->all();

        $user['password'] = GeneralController::parseToSha256($user['password']);
        if (!User::create($user)) {
            return response()->json([
                'success' => false,
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
        $password = $user['password'];

        foreach ($req as $index => $value) {
            if ($value != $user[$index]) {
                $user[$index] = $value;
            }
        }

        if ($password != $user['password']) {
            $user['password'] = GeneralController::parseToSha256($req['password']);
        }

        if (!$user->save()) {
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
