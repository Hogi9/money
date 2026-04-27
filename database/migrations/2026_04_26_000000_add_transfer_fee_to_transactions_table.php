<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('transfer_fee', 15, 2)->default(0)->after('amount');
            $table->enum('fee_bearer', ['sender', 'receiver'])->nullable()->after('transfer_fee');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['transfer_fee', 'fee_bearer']);
        });
    }
};
