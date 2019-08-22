<?php

namespace App\Http\Controllers;

use App\Model\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function create(Request $request)
    {
        $volunteer = $request->all();
        if (!Volunteer::create($volunteer)) {
            return response()->json([
                'success' => false,
                'volunteer' => $volunteer
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created volunteer',
            'volunteer' => $volunteer
        ], 201);
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $req = $request->all();

        foreach ($req as $index => $value) {
            if ($value != $volunteer[$index]) {
                $volunteer[$index] = $value;
            }
        }

        if (!$volunteer->save()) {
            return response()->json([
                'success' => false,
                'volunteer' => $volunteer
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Volunteer updated successfully',
            'volunteer' => $volunteer
        ], 201);
    }

    public function findAll()
    {
        $volunteers = Volunteer::all();

        return response()->json([
            'success' => true,
            'message' => 'Volunteers successfully found',
            'volunteers' => $volunteers
        ], 200);
    }

    public function findById(Volunteer $volunteer)
    {
        return response()->json([
            'success' => true,
            'message' => 'Volunteer successfully found',
            'volunteer' => $volunteer
        ], 200);
    }

    public function destroy(Volunteer $volunteer)
    {
        if (!$volunteer->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Volunteer successfully deleted'
        ], 200);
    }
}
