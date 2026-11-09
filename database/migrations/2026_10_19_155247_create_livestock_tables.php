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
        // 1. Kategori Ternak
        Schema::create('livestock_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sapi Potong Limousin, Sapi Perah, Kambing Boer, Kambing Peranakan Etawa, Ayam Petelur
            $table->enum('type', ['sapi', 'kambing_domba', 'unggas', 'lainnya']);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Kelompok / Batch Ternak
        Schema::create('livestock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('livestock_categories')->cascadeOnDelete();
            $table->string('code')->unique(); // BATCH-SP-2026-01
            $table->string('name'); // Penggemukan Sapi Limousin Periode 1
            $table->string('cage_location'); // Kandang Komunal Blok B
            $table->integer('initial_quantity')->default(0); // Ekor
            $table->integer('current_quantity')->default(0); // Ekor saat ini
            $table->integer('male_count')->default(0);
            $table->integer('female_count')->default(0);
            $table->integer('birth_count')->default(0);
            $table->integer('death_count')->default(0);
            $table->integer('sold_count')->default(0);
            $table->decimal('initial_purchase_cost', 15, 2)->default(0); // Biaya beli bibit/bakalan
            $table->date('entry_date'); // Tanggal masuk kandang
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Biaya Operasional Peternakan (Pakan & Kesehatan)
        Schema::create('livestock_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('livestock_batches')->cascadeOnDelete();
            $table->enum('category', ['pakan_konsentrat', 'hijauan', 'vaksin_obat', 'vitamin', 'perawatan_kandang', 'tenaga_kerja', 'lainnya']);
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // sak, kg, botol, pick-up
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->date('date');
            $table->string('supplier_or_doctor')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Penjualan Ternak dan Hasil Peternakan
        Schema::create('livestock_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('livestock_batches')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('sale_type', ['hewan_hidup', 'daging', 'susu', 'telur', 'pupuk_kandang', 'lainnya']);
            $table->string('item_description'); // 2 Ekor Sapi Siap Qurban / 100 Liter Susu Murni / 50 Karung Pupuk Kohe
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // ekor, liter, kg, karung
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->date('sale_date');
            $table->string('buyer_name');
            $table->string('buyer_phone')->nullable();
            $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('lunas');
            $table->string('payment_method')->default('tunai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_sales');
        Schema::dropIfExists('livestock_operations');
        Schema::dropIfExists('livestock_batches');
        Schema::dropIfExists('livestock_categories');
    }
};
