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
    Schema::create('folders', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        // parent_id digunakan agar folder bisa berada di dalam folder lain (Sub-folder)
        $table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
