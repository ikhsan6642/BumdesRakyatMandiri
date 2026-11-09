<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cfd_events', function (Blueprint $table) {
            $table->time('collection_start_time')->default('07:00:00');
            $table->time('collection_end_time')->default('11:00:00');
        });
    }

    public function down(): void
    {
        Schema::table('cfd_events', function (Blueprint $table) {
            $table->dropColumn(['collection_start_time', 'collection_end_time']);
        });
    }
};
