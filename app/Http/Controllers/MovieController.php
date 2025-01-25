<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieDetail;
use App\Services\OmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;

class MovieController extends BaseController
{
    protected $omdbService;

    public function __construct(OmdbService $omdbService)
    {
        $this->middleware(['api.key']);   
        $this->omdbService = $omdbService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|max:'.date('Y'),
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $movie = Movie::create($validator->validated());

        // Fetch additional movie details from OMDb
        $omdbData = $this->omdbService->getMovieDetails(
            $movie->title,
            $movie->release_year
        );

        if ($omdbData) {
            MovieDetail::create([
                'movie_id' => $movie->id,
                'imdb_id' => $omdbData['imdbID'] ?? '',
                'genre' => $omdbData['Genre'] ?? '',
                'director' => $omdbData['Director'] ?? '',
                'actors' => $omdbData['Actors'] ?? '',
                'plot' => $omdbData['Plot'] ?? '',
                'runtime' => $omdbData['Runtime'] ?? ''
            ]);
        }

        return response()->json($movie, 201);
    }
    
    // update Film
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'release_year'=>'nullable|integer|max:'.date('Y'),
            'user_id'=>'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors()
            ], 400);
        }

        $movie = Movie::findOrFail($id);
        $movie->update($validator->validate());

        // Resync OMDb details
        $movie->details()->delete();
        $omdbData = $this->omdbService->getMovieDetails(
            $movie->title,
            $movie->release_year
        );

        if ($omdbData && $omdbData['Response']==='True'){
            MovieDetail::create([
                'movie_id'=>$movie->id,
                'imdb_id'=>$omdbData['imdbID']??'',
                'genre'=>$omdbData['Genre']??'',
                'director'=>$omdbData['Director']??'',
                'actors'=>$omdbData['Actors']??'',
                'plot'=>$omdbData['Plot']??'',
                'runtime'=>$omdbData['Runtime']??''
            ]);
        }

        return response()->json($movie, 200);
    }

    // Delete Film
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete(); // Cascading delete via foreign key constraints\

        return response()->json(null, 204);
    }

    // Read Films
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $title = $request->input('title');
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'asc');
    
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }
    
        $query = Movie::with(['user', 'details', 'reviews.user'])
            ->where('user_id', $userId);
    
        // Title search
        if ($title) {
            $query->where('title', 'LIKE', "%{$title}%");
        }
    
        // Conditional sorting
        if ($sortBy && in_array($sortBy, ['title', 'release_year'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Default: sort by ID ascending if no sort parameter
            $query->orderBy('id', 'asc');
        }
    
        $movies = $query->get()->map(function($movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description,
                'release_year' => $movie->release_year,
                'details' => $movie->details ? [
                    'imdb_id' => $movie->details->imdb_id,
                    'genre' => $movie->details->genre,
                    'director' => $movie->details->director,
                    'actors' => $movie->details->actors,
                    'plot' => $movie->details->plot,
                    'runtime' => $movie->details->runtime
                ] : null,
                'users' => [
                    'id' => $movie->user->id,
                    'name' => $movie->user->name,
                    'email' => $movie->user->email,
                ],
                'reviews' => $movie->reviews->map(function($review) {
                    return [
                        'user_id' => $review->user_id,
                        'rating' => $review->rating,
                        'comment' => $review->comment
                    ];
                })
            ];
        });
    
        return response()->json($movies);
    }
}
