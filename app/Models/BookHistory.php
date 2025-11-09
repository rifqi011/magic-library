<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookHistory extends Model
{
    protected $fillable = [
        'book_id',
        'member_id',
        'borrow_date',
        'return_date',
        'status',
        'fine',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'fine' => 'decimal:2',
    ];

    public $timestamps = true;

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
