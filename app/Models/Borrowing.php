<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'created_by',
        'member_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($borrowing) {
            if (Auth::check()) {
                $borrowing->created_by = Auth::id();
            }
        });

        static::created(function ($borrowing) {
            // Create history records for each book when borrowing is created
            foreach ($borrowing->borrowingDetails as $detail) {
                BookHistory::create([
                    'book_id' => $detail->book_id,
                    'member_id' => $borrowing->member_id,
                    'borrow_date' => $borrowing->borrow_date,
                    'return_date' => null,
                    'status' => 'borrowed',
                    'fine' => 0,
                ]);
            }
        });

        static::updating(function ($borrowing) {
            if ($borrowing->isDirty('status') || $borrowing->isDirty('return_date')) {
                $borrowing->calculateFine();
            }
        });

        static::deleting(function ($borrowing) {
            if ($borrowing->status !== 'returned') {
                foreach ($borrowing->borrowingDetails as $detail) {
                    $book = $detail->book;
                    if ($book) {
                        $book->increment('stock', $detail->quantity);
                    }
                }
            }
        });
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function calculateFine(): void
    {
        // Calculate fine based on late days
        // Rp 1000 per day per book
        $finePerDay = 1000;

        if ($this->return_date) {
            $returnDate = $this->return_date;
        } else {
            $returnDate = now();
        }

        if ($returnDate->greaterThan($this->due_date)) {
            $lateDays = $returnDate->diffInDays($this->due_date);
            $totalBooks = $this->borrowingDetails()->sum('quantity');
            $this->fine = $lateDays * $finePerDay * $totalBooks;

            // Update status to late if not already returned
            if ($this->status !== 'returned') {
                $this->status = 'late';
            }
        } else {
            $this->fine = 0;
        }
    }

    public function returnBooks(): void
    {
        DB::transaction(function () {
            $this->return_date = now();
            $this->calculateFine();
            $this->status = 'returned';
            $this->save();

            // Return stock for each book
            foreach ($this->borrowingDetails as $detail) {
                $book = $detail->book;
                $book->increment('stock', $detail->quantity);

                // Update existing history records
                BookHistory::where('book_id', $detail->book_id)
                    ->where('member_id', $this->member_id)
                    ->where('borrow_date', $this->borrow_date)
                    ->whereNull('return_date')
                    ->update([
                        'return_date' => $this->return_date,
                        'status' => $this->status,
                        'fine' => $this->fine / $this->borrowingDetails->count(),
                    ]);
            }
        });
    }
}
