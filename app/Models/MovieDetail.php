<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieDetail extends Model
{
    protected $fillable = [
        'movie_id', 'imdb_id', 'genre', 'director', 
        'actors', 'plot', 'runtime'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
