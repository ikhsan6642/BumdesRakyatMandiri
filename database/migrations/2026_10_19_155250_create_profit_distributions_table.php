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
        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->year('fiscal_year'); // Tahun Buku (contoh 2026)
            $table->decimal('total_net_profit', 15, 2); // Total Laba Bersih Konsolidasi
            
            // Persentase & Nominal Alokasi Bagi Hasil sesuai AD/ART BUMDes & Permendesa
            $table->decimal('pades_percentage', 5, 2)->default(35.00); // 35% Pendapatan Asli Desa
            $table->decimal('pades_amount', 15, 2);

            $table->decimal('capital_reserve_percentage', 5, 2)->default(25.00); // 25% Cadangan Modal
            $table->decimal('capital_reserve_amount', 15, 2);

            $table->decimal('management_incentive_percentage', 5, 2)->default(20.00); // 20% Jasa Pengurus / Pengawas / Karyawan
            $table->decimal('management_incentive_amount', 15, 2);

            $table->decimal('social_fund_percentage', 5, 2)->default(10.00); // 10% Dana Sosial Warga Miskin
            $table->decimal('social_fund_amount', 15, 2);

            $table->decimal('education_fund_percentage', 5, 2)->default(10.00); // 10% Dana Pendidikan / Pembinaan
            $table->decimal('education_fund_amount', 15, 2);

            $table->enum('status', ['draft', 'disetujui_musdes', 'disalurkan'])->default('draft');
            $table->date('distribution_date')->nullable();
            $table->string('approved_by')->nullable(); // Nama Kepala Desa / Ketua BPD
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_distributions');
    }
};
