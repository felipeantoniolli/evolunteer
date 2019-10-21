<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class RatingController extends Controller
{
    public function create(Request $request)
    {
        $rating = $request->all();
        $rules = Rating::insertRules();

        $validator = Validator::make(
            $rating,
            $rules['rules'],
            $rules['messages']
        );

        if ($validator->fails()) {
            return GeneralController::jsonReturn(
                false,
                401,
                $rating,
                'Validation error.',
                $validator->errors()
            );
        }

        $requiredRules = Volunteer::requiredRules($rating);

        if ($requiredRules) {
            return GeneralController::jsonReturn(
                false,
                401,
                $requiredRules,
                'Validation error.',
                $uniqueRules
            );
        }

        if (!Rating::create($rating)) {
            return GeneralController::jsonReturn(true, 400, $rating, 'Rating not created.');
        }

        return GeneralController::jsonReturn(true, 201, $rating, 'Successfully created Rating.');
    }

    public function update(Request $request, Rating $rating)
    {
        $req = $request->all();
        $rules = Rating::rules();

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

        $requiredRules = Volunteer::requiredRules($req);

        if ($requiredRules) {
            return GeneralController::jsonReturn(
                false,
                401,
                $requiredRules,
                'Validation error.',
                $uniqueRules
            );
        }

        foreach ($req as $index => $value) {
            if ($value != $rating[$index]) {
                $rating->$index = $value;
            }
        }

        if (!$rating = $rating->save()) {
            return GeneralController::jsonReturn(false, 400, $rating, 'Rating not updated.');
        }

        return GeneralController::jsonReturn(true, 200, $req, 'Rating updated successfully.');
    }

    public function findAll()
    {
        $ratings = Rating::all();

        if (!$ratings) {
            return GeneralController::jsonReturn(false, 400, [], 'Ratings not found.');
        }

        return GeneralController::jsonReturn(true, 200, $ratings, 'Ratings successfully found.');
    }

    public function findById(Rating $rating)
    {
        if (!$rating) {
            return GeneralController::jsonReturn(false, 400, [], 'Rating not found.');
        }

        return GeneralController::jsonReturn(true, 200, $rating, 'Rating successfully found.');
    }

    public function destroy(Rating $rating)
    {
        if (!$rating->delete()) {
            return GeneralController::jsonReturn(false, 400, $rating, 'Rating not deleted');
        }

        return GeneralController::jsonReturn(true, 200, $rating, 'Rating successfully deleted');
    }
}
