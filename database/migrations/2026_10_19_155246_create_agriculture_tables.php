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
        // 1. Siklus Tanam dan Panen Pertanian
        Schema::create('agriculture_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // TNM-2026-001
            $table->string('commodity_name'); // Padi Ciherang, Jagung Hibrida, Bawang Merah, Semangka
            $table->string('land_location'); // Blok Sawah Krajan, Lahan Kas Desa Barat
            $table->decimal('land_area_ha', 8, 2)->default(1.00); // Luas hektar
            $table->date('start_date');
            $table->date('estimated_harvest_date')->nullable();
            $table->date('actual_harvest_date')->nullable();
            $table->enum('status', ['persiapan', 'tanam', 'rawat', 'panen', 'selesai'])->default('persiapan');
            $table->decimal('total_yield_kg', 12, 2)->default(0); // Hasil Panen (Kg)
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Biaya Produksi Tanam (Bibit, Pupuk, Tenaga Kerja)
        Schema::create('agriculture_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('agriculture_cycles')->cascadeOnDelete();
            $table->enum('category', ['bibit', 'pupuk', 'pestisida', 'tenaga_kerja', 'sewa_lahan', 'lainnya']);
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // sak, botol, kg, orang-hari
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->date('expense_date');
            $table->string('supplier_or_worker')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Penjualan Hasil Panen Pertanian
        Schema::create('agriculture_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('agriculture_cycles')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('buyer_name');
            $table->string('buyer_phone')->nullable();
            $table->decimal('quantity_sold_kg', 12, 2);
            $table->decimal('price_per_kg', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->date('sale_date');
            $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('lunas');
            $table->string('payment_method')->default('tunai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Alsintan (Alat & Mesin Pertanian) BUMDes
        Schema::create('agriculture_machineries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ALST-001
            $table->string('name'); // Traktor Quick G1000, Pompa Air 3 Inch, Combine Harvester
            $table->string('brand_model')->nullable();
            $table->year('procurement_year')->nullable();
            $table->decimal('rental_rate_per_day', 15, 2); // Tarif sewa per hari
            $table->enum('condition', ['sangat_baik', 'baik', 'perlu_servis', 'rusak'])->default('baik');
            $table->enum('status', ['tersedia', 'disewa', 'perbaikan'])->default('tersedia');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Transaksi Sewa Alsintan
        Schema::create('agriculture_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machinery_id')->constrained('agriculture_machineries')->cascadeOnDelete();
            $table->string('rental_code')->unique(); // SWA-2026-001
            $table->string('renter_name'); // Nama Petani Penyewa
            $table->string('renter_phone')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days')->default(1);
            $table->decimal('rate_per_day', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('lunas');
            $table->enum('status', ['berjalan', 'dikembalikan', 'dibatalkan'])->default('berjalan');
            $table->date('actual_return_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agriculture_rentals');
        Schema::dropIfExists('agriculture_machineries');
        Schema::dropIfExists('agriculture_sales');
        Schema::dropIfExists('agriculture_productions');
        Schema::dropIfExists('agriculture_cycles');
    }
};
