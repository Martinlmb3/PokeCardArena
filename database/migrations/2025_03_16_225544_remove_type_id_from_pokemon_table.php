<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['type_id']);
            // Then drop the column
            $table->dropColumn('type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemon', function (Blueprint $table) {
            // Re-add the column and foreign key if we need to roll back
            $table->foreignId('type_id')->nullable()->constrained('types')
                  ->onUpdate('cascade')->onDelete('set null');
        });
    }
};
