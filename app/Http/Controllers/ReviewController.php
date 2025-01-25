<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class ReviewController extends Controller
{
    // add Review
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id'=>'required|exists:movies,id',
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string',
            'user_id'=> 'required|exists:users,id'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'errors'=>$validator->errors()
            ], 400);
        }

        $review = Review::create($validator->validate());

        return response()->json($review, 201);
    }
}
