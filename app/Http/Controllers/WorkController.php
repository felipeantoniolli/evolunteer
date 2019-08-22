<?php

namespace App\Http\Controllers;

use App\Model\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function create(Request $request)
    {
        $work = $request->all();
        if (!Work::create($work)) {
            return response()->json([
                'success' => false,
                'work' => $work
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created Work',
            'work' => $work
        ], 201);
    }

    public function update(Request $request, Work $work)
    {
        $req = $request->all();

        foreach ($req as $index => $value) {
            if ($value != $work[$index]) {
                $work[$index] = $value;
            }
        }

        if (!$work->save()) {
            return response()->json([
                'success' => false,
                'work' => $work
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Work updated successfully',
            'work' => $work
        ], 201);
    }

    public function findAll()
    {
        $works = Work::findAll();

        return response()->json([
            'success' => true,
            'message' => 'Work successfully found',
            'works' => $works
        ], 200);
    }

    public function findById(Work $work)
    {
        return response()->json([
            'success' => true,
            'message' => 'Work successfully found',
            'work' => $work
        ], 200);
    }

    public function destroy(Work $work)
    {
        if (!$work->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Work successfully deleted'
        ], 200);
    }
}

