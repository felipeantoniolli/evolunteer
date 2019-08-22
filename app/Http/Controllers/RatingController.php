<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create(Request $request)
    {
        $rating = $request->all();
        if (!Rating::create($rating)) {
            return response()->json([
                'success' => false,
                'rating' => $rating
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created Rating',
            'rating' => $rating
        ], 201);
    }

    public function update(Request $request, Rating $rating)
    {
        $req = $request->all();

        foreach ($req as $index => $value) {
            if ($value != $rating[$index]) {
                $rating[$index] = $value;
            }
        }

        if (!$rating->save()) {
            return response()->json([
                'success' => false,
                'rating' => $rating
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating updated successfully',
            'rating' => $rating
        ], 201);
    }

    public function findAll()
    {
        $ratings = Rating::findAll();

        return response()->json([
            'success' => true,
            'message' => 'Ratings successfully found',
            'ratings' => $ratings
        ], 200);
    }

    public function findById(Rating $rating)
    {
        return response()->json([
            'success' => true,
            'message' => 'Rating successfully found',
            'rating' => $rating
        ], 200);
    }

    public function destroy(Rating $rating)
    {
        if (!$rating->delete()) {
            return response()->json([
                'success' => false
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating successfully deleted'
        ], 200);
    }
}
