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
        // 1. Tambah kolom role, phone, unit_code pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('direktur')->after('email'); // direktur, bendahara, admin_unit
            $table->string('unit_code')->nullable()->after('role'); // pertanian, peternakan, cfd, pamsimas, pusat
            $table->string('phone')->nullable()->after('unit_code');
        });

        // 2. Unit Usaha BUMDes
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // pertanian, peternakan, cfd, pamsimas, pusat
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('icon')->default('bi-briefcase');
            $table->string('color')->default('emerald');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Bagan Akun (Chart of Accounts / COA)
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // contoh: 1-1001, 4-1001
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->string('unit_code')->nullable(); // null = kantor pusat / umum
            $table->enum('normal_balance', ['debit', 'credit'])->default('debit');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Buku Kas & Transaksi Finansial BUMDes
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // BKM-202610-001, BKK-202610-001
            $table->string('unit_code')->default('pusat'); // pertanian, peternakan, cfd, pamsimas, pusat
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->enum('type', ['income', 'expense']); // Kas Masuk / Kas Keluar
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->enum('payment_method', ['tunai', 'transfer_bank'])->default('tunai');
            $table->string('reference_type')->nullable(); // pamsimas, cfd, pertanian, peternakan, umum
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('recipient_or_payer')->nullable(); // Dari siapa / Kepada siapa
            $table->text('description');
            $table->string('receipt_number')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('business_units');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'unit_code', 'phone']);
        });
    }
};
