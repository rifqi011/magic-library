<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\BookHistory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::where('status', 'active')->get();
        $books = Book::where('status', 'active')->where('stock', '>', 0)->get();

        if ($members->isEmpty() || $books->isEmpty()) {
            $this->command->warn('No members or books available for borrowing seeder');
            return;
        }

        $borrowingData = [
            // returned
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(23),
                'return_date' => Carbon::now()->subDays(25),
                'status' => 'returned',
                'fine' => 0,
                'books_count' => rand(1, 3),
            ],
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(25),
                'due_date' => Carbon::now()->subDays(18),
                'return_date' => Carbon::now()->subDays(20),
                'status' => 'returned',
                'fine' => 0,
                'books_count' => rand(1, 2),
            ],
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(13),
                'return_date' => Carbon::now()->subDays(10),
                'status' => 'returned',
                'fine' => 3000, // Terlambat 3 hari
                'books_count' => rand(1, 2),
            ],
            // borrowed
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(2),
                'return_date' => null,
                'status' => 'borrowed',
                'fine' => 0,
                'books_count' => rand(1, 3),
            ],
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(3),
                'due_date' => Carbon::now()->addDays(4),
                'return_date' => null,
                'status' => 'borrowed',
                'fine' => 0,
                'books_count' => rand(2, 4),
            ],
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(1),
                'due_date' => Carbon::now()->addDays(6),
                'return_date' => null,
                'status' => 'borrowed',
                'fine' => 0,
                'books_count' => rand(1, 2),
            ],
            // late
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(15),
                'due_date' => Carbon::now()->subDays(8),
                'return_date' => null,
                'status' => 'late',
                'fine' => 0,
                'books_count' => rand(1, 2),
            ],
            [
                'member' => $members->random(),
                'borrow_date' => Carbon::now()->subDays(12),
                'due_date' => Carbon::now()->subDays(5),
                'return_date' => null,
                'status' => 'late',
                'fine' => 0,
                'books_count' => rand(1, 3),
            ],
        ];

        foreach ($borrowingData as $data) {
            $borrowing = Borrowing::create([
                'created_by' => 1,
                'member_id' => $data['member']->id,
                'borrow_date' => $data['borrow_date'],
                'due_date' => $data['due_date'],
                'return_date' => $data['return_date'],
                'status' => $data['status'],
                'fine' => $data['fine'],
            ]);

            $selectedBooks = $books->random($data['books_count']);

            foreach ($selectedBooks as $book) {
                if ($book->stock <= 0) {
                    continue;
                }

                $quantity = 1;

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'book_id' => $book->id,
                    'quantity' => $quantity,
                ]);

                if ($data['status'] !== 'returned') {
                    $book->decrement('stock', $quantity);
                }

                $historyData = [
                    'book_id' => $book->id,
                    'member_id' => $data['member']->id,
                    'borrow_date' => $data['borrow_date'],
                    'return_date' => $data['return_date'],
                    'status' => $data['status'],
                    'fine' => 0,
                ];

                if ($data['status'] === 'returned' && $data['fine'] > 0) {
                    $historyData['fine'] = $data['fine'] / $data['books_count'];
                } elseif ($data['status'] === 'late') {
                    $lateDays = Carbon::now()->diffInDays($data['due_date']);
                    $historyData['fine'] = $lateDays * 1000 * $quantity;

                    $borrowing->fine += $historyData['fine'];
                }

                BookHistory::create($historyData);
            }

            if ($data['status'] === 'late') {
                $borrowing->save();
            }

            $this->command->info("Created borrowing for member: {$data['member']->name} with {$data['books_count']} book(s) - Status: {$data['status']}");
        }

        $this->command->info('Borrowing seeder completed!');
        $this->command->info('Total borrowings created: ' . count($borrowingData));
        $this->command->info('- Returned: ' . collect($borrowingData)->where('status', 'returned')->count());
        $this->command->info('- Borrowed: ' . collect($borrowingData)->where('status', 'borrowed')->count());
        $this->command->info('- Late: ' . collect($borrowingData)->where('status', 'late')->count());
    }
}
