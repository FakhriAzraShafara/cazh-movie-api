<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    // Tambahkan properti ini
    protected $fillable = [
        'title',
        'description',
        'release_year',
        'user_id',
    ];

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
