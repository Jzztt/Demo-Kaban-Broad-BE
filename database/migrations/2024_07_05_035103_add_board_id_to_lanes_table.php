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
        Schema::table('lanes', function (Blueprint $table) {
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lanes', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropColumn('board_id');
        });
    }
};
