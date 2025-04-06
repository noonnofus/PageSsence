<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'genre',
        'price',
        'publication_year',
    ];

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_books');
    }

    public function ratings()
    {
        return $this->hasMany(BookRate::class);
    }
}
