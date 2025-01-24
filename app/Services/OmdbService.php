<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

// app/Services/OmdbService.php
class OmdbService{
    public function getMovieDetails($title, $year = null){
        $apiKey = config('app.omdb_api');
        $url = "https://www.omdbapi.com/?i=tt3896198&apikey={$apiKey}".urlencode($title);

        if ($year){
            $url .= ".&y={$year}";
        }

        $response = Http::get($url);

        if($response->successful()){
            return $response->json();
        }

        return [
            'error' => 'failed to fetch movie details. Plase Try again'
        ];
    }
}