<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChat extends Model
{
    use HasFactory;

    protected $table = 'book_chat';

    protected $fillable = [
        'book_id', 
        'user_id', 
        'message',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
