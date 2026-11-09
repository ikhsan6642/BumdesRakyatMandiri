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
        // 1. Zona Lapak CFD
        Schema::create('cfd_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Zona A - Kuliner Utama, Zona B - Pakaian & Kerajinan, Zona C - Mainan & Jasa
            $table->string('code')->unique(); // Z-A, Z-B, Z-C
            $table->decimal('default_fee', 15, 2); // Tarif retribusi standar (misal Rp 15.000 / lapak)
            $table->integer('capacity_spots')->default(50); // Kapasitas lapak
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Pedagang CFD (UMKM)
        Schema::create('cfd_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code')->unique(); // CFD-V-001
            $table->string('name'); // Toko / Lapak (contoh: Bakso Mantap Pak Budi)
            $table->string('owner_name'); // Nama Pemilik
            $table->string('phone')->nullable();
            $table->foreignId('preferred_zone_id')->nullable()->constrained('cfd_zones')->nullOnDelete();
            $table->string('spot_number')->nullable(); // Nomor Lapak tetap jika ada
            $table->enum('category', ['kuliner_makanan', 'kuliner_minuman', 'fashion_pakaian', 'kerajinan_souvenir', 'mainan_hiburan', 'pertanian_organik', 'lainnya']);
            $table->string('origin_village')->default('Desa Rakyat Mandiri'); // Warga Desa sendiri atau Luar Desa
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Event CFD Mingguan (Setiap Hari Minggu)
        Schema::create('cfd_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_code')->unique(); // CFD-EVT-2026-W42
            $table->string('name'); // CFD Rakyat Mandiri Pekan 42
            $table->date('event_date');
            $table->integer('total_stalls_occupied')->default(0); // Jumlah lapak buka
            $table->decimal('total_stall_revenue', 15, 2)->default(0); // Total uang karcis lapak
            $table->decimal('parking_revenue', 15, 2)->default(0); // Total uang parkir BUMDes
            $table->decimal('cleanliness_cost', 15, 2)->default(0); // Biaya kebersihan & sampah
            $table->decimal('security_cost', 15, 2)->default(0); // Biaya petugas keamanan & linmas
            $table->decimal('sound_electricity_cost', 15, 2)->default(0); // Listrik & Genset
            $table->decimal('net_revenue', 15, 2)->default(0); // Laba bersih pekan ini
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai'])->default('terjadwal');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Karcis Retribusi Lapak CFD
        Schema::create('cfd_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('cfd_events')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('cfd_vendors')->nullOnDelete();
            $table->string('vendor_name_manual')->nullable(); // Jika pedagang insidental baru
            $table->foreignId('zone_id')->constrained('cfd_zones')->cascadeOnDelete();
            $table->string('ticket_number')->unique(); // KRC-20261019-001
            $table->string('spot_number'); // No Lapak A-12
            $table->decimal('fee_amount', 15, 2); // Nominal karcis
            $table->decimal('electricity_fee', 15, 2)->default(0); // Biaya tambahan colokan listrik jika ada
            $table->decimal('cleanliness_fee', 15, 2)->default(0); // Biaya sampah
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_status', ['lunas', 'belum_bayar'])->default('lunas');
            $table->string('collected_by')->nullable(); // Petugas pemungut
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cfd_tickets');
        Schema::dropIfExists('cfd_events');
        Schema::dropIfExists('cfd_vendors');
        Schema::dropIfExists('cfd_zones');
    }
};
