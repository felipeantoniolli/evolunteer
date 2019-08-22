<?php

namespace App\Http\Controllers;

use App\Model\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function create(Request $request)
    {
        $institution = $request->all();
        if (!Institution::create($institution)) {
            return response()->json([
                'success' => false,
                'institution' => $institution
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created institution',
            'institution' => $institution
        ], 201);
    }

    public function update(Request $request, Institution $institution)
    {
        $req = $request->all();

        foreach ($req as $index => $value) {
            if ($value != $institution[$index]) {
                $institution[$index] = $value;
            }
        }

        if (!$institution->save()) {
            return response()->json([
                'success' => false,
                'institution' => $institution
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Institution updated successfully',
            'institution' => $institution
        ], 201);
    }

    public function findAll()
    {
        $institutions = Institution::findAll();

        return response()->json([
            'success' => true,
            'message' => 'Institution successfully found',
            'institutions' => $institutions
        ], 200);
    }

    public function findById(Institution $institution)
    {
        return response()->json([
            'success' => true,
            'message' => 'Institution successfully found',
            'institution' => $institution
        ], 200);
    }

    public function destroy(Institution $institution)
    {
        if (!$institution->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Institution successfully deleted'
        ], 200);
    }
}
