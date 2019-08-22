<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolicitationController extends Controller
{
    public function create(Request $request)
    {
        $solicitation = $request->all();
        if (!Solicitation::create($solicitation)) {
            return response()->json([
                'success' => false,
                'solicitation' => $solicitation
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created Solicitation',
            'solicitation' => $solicitation
        ], 201);
    }

    public function update(Request $request, Solicitation $solicitation)
    {
        $req = $request->all();

        foreach ($req as $index => $value) {
            if ($value != $solicitation[$index]) {
                $solicitation[$index] = $value;
            }
        }

        if (!$solicitation->save()) {
            return response()->json([
                'success' => false,
                'solicitation' => $solicitation
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Solicitation updated successfully',
            'solicitation' => $solicitation
        ], 201);
    }

    public function findAll()
    {
        $solicitations = Solicitation::findAll();

        return response()->json([
            'success' => true,
            'message' => 'Solicitation successfully found',
            'solicitations' => $solicitations
        ], 200);
    }

    public function findById(Solicitation $solicitation)
    {
        return response()->json([
            'success' => true,
            'message' => 'Solicitation successfully found',
            'solicitation' => $solicitation
        ], 200);
    }

    public function destroy(Solicitation $solicitation)
    {
        if (!$solicitation->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Solicitation successfully deleted'
        ], 200);
    }
}

