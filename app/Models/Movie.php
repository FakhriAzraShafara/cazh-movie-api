<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->hasOne(MovieDetail::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
