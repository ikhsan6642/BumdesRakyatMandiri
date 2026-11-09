<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('transactions')->whereIn('unit_code', ['pertanian', 'peternakan'])->delete();

        DB::table('users')
            ->whereIn('unit_code', ['pertanian', 'peternakan'])
            ->delete();

        DB::table('business_units')->whereIn('code', ['pertanian', 'peternakan'])->delete();

        foreach ([
            'agriculture_rentals',
            'agriculture_machineries',
            'agriculture_sales',
            'agriculture_productions',
            'agriculture_cycles',
            'livestock_sales',
            'livestock_operations',
            'livestock_batches',
            'livestock_categories',
            'profit_distributions',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_id');
        });

        Schema::dropIfExists('accounts');
    }

    public function down(): void
    {
        throw new RuntimeException('This migration permanently removes feature data. Restore from a database backup instead.');
    }
};
