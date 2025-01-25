<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OmdbService
{
    public function getMovieDetails($title, $year = null)
    {
        $apiKey = config('app.omdb_api');
        $url = "http://www.omdbapi.com/?apikey={$apiKey}&t=" . urlencode($title);

        if ($year) {
            $url .= "&y={$year}";
        }

        try {
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Additional validation
                if (isset($data['Response']) && $data['Response'] === 'True') {
                    return $data;
                }
                
                Log::warning('OMDb API returned no results', [
                    'title' => $title,
                    'year' => $year
                ]);
            }
        } catch (\Exception $e) {
            Log::error('OMDb API request failed', [
                'error' => $e->getMessage(),
                'title' => $title,
                'year' => $year
            ]);
        }

        return null;
    }
}