<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OverdueTestSeeder extends Seeder
{
    public function run(): void
    {
        // Create test reader
        $reader = User::firstOrCreate(
            ['email' => 'reader@test.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'reader',
            ]
        );

        // Get some existing books
        $books = Book::take(3)->get();

        if ($books->count() < 3) {
            $this->command->info('Not enough books. Please add at least 3 books first.');
            return;
        }

        // Create overdue borrows (return_date in the past)
        foreach ($books as $i => $book) {
            Borrow::firstOrCreate(
                [
                    'user_id' => $reader->id,
                    'book_id' => $book->id,
                    'status' => 'late',
                ],
                [
                    'borrower_name' => $reader->name,
                    'borrower_address' => 'Jl. Test No. ' . ($i + 1),
                    'borrow_date' => now()->subDays(30 + $i * 5),
                    'return_date' => now()->subDays(10 + $i * 3),
                    'status' => 'late',
                ]
            );
        }

        $this->command->info('Test reader "Budi Santoso" (reader@test.com / password) created with 3 overdue borrows.');
    }
}
