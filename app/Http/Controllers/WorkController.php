<?php

namespace App\Http\Controllers;

use App\Model\Work;
use Illuminate\Http\Request;
use Validator;

class WorkController extends Controller
{
    public function create(Request $request)
    {
        $work = $request->all();
        $rules = Work::rules();

        $validator = Validator::make(
            $work,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $work,
                'Validation error.',
                $validator->errors()
            );
        }

        if (!Work::create($work)) {
            return GeneralController::jsonReturn(true, 400, $work, 'Work not created.');
        }

        return GeneralController::jsonReturn(true, 201, $work, 'Successfully created Work.');
    }

    public function update(Request $request, Work $work)
    {
        $req = $request->all();
        $rules = Work::rules();

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

        foreach ($req as $index => $value) {
            if ($value != $work[$index]) {
                $work->$index = $value;
            }
        }

        if (!$work = $work->save()) {
            return GeneralController::jsonReturn(false, 400, $work, 'Work not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'Work updated successfully.');
    }

    public function findAll()
    {
        $works = Work::all();

        if (!$works) {
            return GeneralController::jsonReturn(false, 400, [], 'Works not found.');
        }

        return GeneralController::jsonReturn(true, 200, $works, 'Works successfully found.');
    }

    public function findById(Work $work)
    {
        if (!$work) {
            return GeneralController::jsonReturn(false, 400, [], 'Work not found.');
        }

        return GeneralController::jsonReturn(true, 200, $work, 'Work successfully found.');
    }

    public function findByInstitutionId(Request $request)
    {
        $req = $request->all();

        $works = Work::where('id_institution', $req['id_institution'])->get();

        return GeneralController::jsonReturn(true, 200, $works, 'Works successfully found.');
    }

    public function destroy(Work $work)
    {
        if (!$work->delete()) {
            return GeneralController::jsonReturn(false, 400, $work, 'Work not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $work, 'Work successfully deleted');
    }
}

