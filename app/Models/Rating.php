<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'rating',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
