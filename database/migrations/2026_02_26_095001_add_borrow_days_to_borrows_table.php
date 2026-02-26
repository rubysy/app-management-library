<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->integer('borrow_days')->nullable()->after('borrower_address');
            $table->date('borrow_date')->nullable()->change();
            $table->date('return_date')->nullable()->change();
        });

        // Change status from enum to string to support new statuses
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropColumn('borrow_days');
            $table->date('borrow_date')->nullable(false)->change();
            $table->date('return_date')->nullable(false)->change();
        });

        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('active', 'returned', 'late') DEFAULT 'active'");
    }
};
