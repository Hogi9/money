<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['categories', 'transaction_names', 'wallets', 'transactions'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('uuid', 36)->nullable()->unique()->after('id');
            });

            DB::table($table)->whereNull('uuid')->get()->each(function ($row) use ($table) {
                DB::table($table)->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
            });
        }
    }

    public function down(): void
    {
        foreach (['categories', 'transaction_names', 'wallets', 'transactions'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('uuid');
            });
        }
    }
};
