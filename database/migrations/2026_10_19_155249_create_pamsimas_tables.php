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
        // 1. Pengaturan Tarif Pamsimas
        Schema::create('pamsimas_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate_per_m3_social', 10, 2)->default(1500.00); // Kategori Sosial (Masjid, Musholla, Sekolah)
            $table->decimal('rate_per_m3_household', 10, 2)->default(2500.00); // Kategori Rumah Tangga Warga
            $table->decimal('rate_per_m3_business', 10, 2)->default(4000.00); // Kategori Usaha / Warung / Bengkel
            $table->decimal('subscription_fee', 10, 2)->default(10000.00); // Biaya Beban / Abonemen Bulanan
            $table->decimal('late_fee', 10, 2)->default(5000.00); // Denda Keterlambatan
            $table->integer('due_date_day')->default(20); // Tanggal jatuh tempo tiap bulan (contoh tgl 20)
            $table->string('water_source_info')->default('Sumur Bor Dalam Dusun Krajan & Sumber Mata Air Gunung');
            $table->timestamps();
        });

        // 2. Data Pelanggan Warga Pamsimas
        Schema::create('pamsimas_customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_number')->unique(); // PAM-0001
            $table->string('name'); // Nama Kepala Keluarga / Pelanggan
            $table->string('nik', 20)->nullable();
            $table->string('phone')->nullable();
            $table->string('hamlet'); // Dusun: Dusun Krajan, Dusun Sumber, Dusun Sukamaju
            $table->string('rt_rw'); // RT 02 / RW 03
            $table->string('address')->nullable();
            $table->enum('category', ['rumah_tangga', 'sosial', 'usaha'])->default('rumah_tangga');
            $table->integer('initial_meter')->default(0); // Stand meter awal saat pasang
            $table->date('installation_date')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'segel'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Catatan Stand Meter & Tagihan Rekening Air Bulanan
        Schema::create('pamsimas_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('pamsimas_customers')->cascadeOnDelete();
            $table->string('billing_period', 7); // Format YYYY-MM contoh "2026-10"
            $table->string('bill_number')->unique(); // BILL-PAM-202610-0001
            $table->integer('previous_meter')->default(0); // Meter Lalu
            $table->integer('current_meter')->default(0); // Meter Kini
            $table->integer('usage_m3')->default(0); // Pemakaian (m3) = current - previous
            $table->decimal('rate_per_m3', 10, 2);
            $table->decimal('water_charge', 15, 2); // usage_m3 * rate_per_m3
            $table->decimal('subscription_fee', 15, 2); // Biaya abonemen
            $table->decimal('late_fee', 15, 2)->default(0); // Denda jika lewat tempo
            $table->decimal('total_amount', 15, 2); // Total Tagihan
            $table->date('due_date'); // Jatuh tempo
            $table->enum('payment_status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->nullable(); // tunai, transfer
            $table->string('receipt_number')->nullable(); // No Kwitansi Lunas
            $table->string('collected_by')->nullable(); // Petugas Penagih / Kasir
            $table->date('reading_date')->nullable(); // Tanggal pencatatan meter
            $table->timestamps();
        });

        // 4. Biaya Operasional & Pemeliharaan Pamsimas
        Schema::create('pamsimas_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('maintenance_code')->unique(); // MNT-PAM-2026-001
            $table->string('title'); // Pembayaran Token Listrik PLN Pompa Dusun Krajan
            $table->enum('category', ['listrik_pompa', 'perbaikan_pipa', 'kaporit_kimia', 'honor_operator', 'penggantian_meteran', 'lainnya']);
            $table->decimal('cost', 15, 2);
            $table->date('expense_date');
            $table->string('technician_or_vendor')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pamsimas_maintenances');
        Schema::dropIfExists('pamsimas_usages');
        Schema::dropIfExists('pamsimas_customers');
        Schema::dropIfExists('pamsimas_settings');
    }
};
