<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['income', 'outcome']);
            $table->string('description')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // team_id disiapkan untuk grouping keuangan per tim di masa depan
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
