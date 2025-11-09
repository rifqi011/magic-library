<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $fillable = [
        'borrowing_id',
        'book_id',
        'quantity',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($detail) {
            // Decrease stock when borrowing detail is created
            $borrowing = $detail->borrowing;
            // Only decrease if borrowing exists and not returned
            if ($borrowing && $borrowing->status !== 'returned') {
                $book = $detail->book;
                if ($book && $book->stock >= $detail->quantity) {
                    $book->decrement('stock', $detail->quantity);
                }
            }
        });

        static::deleting(function ($detail) {
            // Return stock when detail is deleted
            $borrowing = $detail->borrowing;
            // Only return stock if borrowing is not returned yet
            if ($borrowing && $borrowing->status !== 'returned') {
                $book = $detail->book;
                if ($book) {
                    $book->increment('stock', $detail->quantity);
                }
            }
        });

        static::updating(function ($detail) {
            if ($detail->isDirty('quantity')) {
                $book = $detail->book;
                $borrowing = $detail->borrowing;

                if ($book && $borrowing && $borrowing->status !== 'returned') {
                    $oldQuantity = $detail->getOriginal('quantity');
                    $newQuantity = $detail->quantity;
                    $difference = $newQuantity - $oldQuantity;

                    if ($difference > 0) {
                        // Increased quantity - decrease stock
                        if ($book->stock >= $difference) {
                            $book->decrement('stock', $difference);
                        }
                    } else {
                        // Decreased quantity - increase stock
                        $book->increment('stock', abs($difference));
                    }
                }
            }
        });
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
