<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pivot table for many-to-many books <-> categories
        Schema::create('categories_relasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['book_id', 'category_id']);
        });

        // Migrate existing category_id data to pivot table
        $books = DB::table('books')->whereNotNull('category_id')->get();
        foreach ($books as $book) {
            DB::table('categories_relasi')->insert([
                'book_id' => $book->id,
                'category_id' => $book->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old category_id column from books
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Create ratings table
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('review')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('genre')->constrained()->nullOnDelete();
        });

        Schema::dropIfExists('book_category');
    }
};
