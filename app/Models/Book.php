<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'category_id',
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'stock',
        'cover_image',
        'description',
        'synopsis'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
