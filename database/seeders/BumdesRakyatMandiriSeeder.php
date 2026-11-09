<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use App\Models\CfdEvent;
use App\Models\CfdTicket;
use App\Models\CfdVendor;
use App\Models\CfdZone;
use App\Models\PamsimasCustomer;
use App\Models\PamsimasSetting;
use App\Models\PamsimasUsage;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BumdesRakyatMandiriSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Joko Supriyono, S.E.',
                'email' => 'direktur@bumdes.desa.id',
                'role' => 'direktur',
                'unit_code' => 'pusat',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Siti Aminah, A.Md.Ak.',
                'email' => 'bendahara@bumdes.desa.id',
                'role' => 'bendahara',
                'unit_code' => 'pusat',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Dian Wahyudi',
                'email' => 'cfd@bumdes.desa.id',
                'role' => 'admin_unit',
                'unit_code' => 'cfd',
                'phone' => '081234567894',
            ],
            [
                'name' => 'Hendra Setiawan',
                'email' => 'pamsimas@bumdes.desa.id',
                'role' => 'admin_unit',
                'unit_code' => 'pamsimas',
                'phone' => '081234567895',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'password' => Hash::make('password')]
            );
        }

        $direktur = User::where('email', 'direktur@bumdes.desa.id')->firstOrFail();
        $bendahara = User::where('email', 'bendahara@bumdes.desa.id')->firstOrFail();

        $units = [
            [
                'code' => 'pusat',
                'name' => 'Kantor Pengelola BUMDes Pusat',
                'description' => 'Manajemen pusat dan administrasi BUMDes Rakyat Mandiri.',
                'manager_name' => $direktur->name,
                'manager_phone' => $direktur->phone,
                'icon' => 'building',
                'color' => 'indigo',
            ],
            [
                'code' => 'cfd',
                'name' => 'Unit Usaha CFD & Pasar UMKM Minggu',
                'description' => 'Car Free Day mingguan, lapak pedagang UMKM, dan parkir terpadu.',
                'manager_name' => 'Dian Wahyudi',
                'manager_phone' => '081234567894',
                'icon' => 'store',
                'color' => 'rose',
            ],
            [
                'code' => 'pamsimas',
                'name' => 'Unit Usaha Pamsimas Tirta Mandiri',
                'description' => 'Penyediaan dan pengelolaan distribusi air bersih desa.',
                'manager_name' => 'Hendra Setiawan',
                'manager_phone' => '081234567895',
                'icon' => 'droplets',
                'color' => 'sky',
            ],
        ];

        foreach ($units as $unit) {
            BusinessUnit::updateOrCreate(['code' => $unit['code']], $unit);
        }

        $zone = CfdZone::updateOrCreate(
            ['code' => 'Z-A'],
            [
                'name' => 'Zona A - Kuliner',
                'default_fee' => 20000,
                'capacity_spots' => 50,
                'description' => 'Zona utama lapak kuliner CFD.',
            ]
        );

        $vendor = CfdVendor::updateOrCreate(
            ['vendor_code' => 'CFD-V-001'],
            [
                'name' => 'Warung UMKM Rakyat',
                'owner_name' => 'Dian Wahyudi',
                'preferred_zone_id' => $zone->id,
                'spot_number' => 'A-01',
                'category' => 'kuliner_makanan',
                'origin_village' => 'Desa Rakyat Mandiri',
                'status' => 'aktif',
            ]
        );

        $event = CfdEvent::updateOrCreate(
            ['event_code' => 'CFD-2026-W42'],
            [
                'name' => 'CFD Rakyat Mandiri Pekan 42',
                'event_date' => '2026-10-18',
                'parking_revenue' => 350000,
                'cleanliness_cost' => 100000,
                'security_cost' => 80000,
                'sound_electricity_cost' => 50000,
                'status' => 'selesai',
            ]
        );

        $ticket = CfdTicket::updateOrCreate(
            ['ticket_number' => 'KRC-20261018-001'],
            [
                'event_id' => $event->id,
                'vendor_id' => $vendor->id,
                'zone_id' => $zone->id,
                'spot_number' => 'A-01',
                'fee_amount' => 20000,
                'electricity_fee' => 0,
                'cleanliness_fee' => 2000,
                'total_amount' => 22000,
                'payment_status' => 'lunas',
                'collected_by' => 'Dian Wahyudi',
                'paid_at' => Carbon::parse('2026-10-18 08:00:00'),
            ]
        );
        $event->calculateNetRevenue();

        PamsimasSetting::updateOrCreate(
            ['id' => 1],
            [
                'rate_per_m3_social' => 1500,
                'rate_per_m3_household' => 2500,
                'rate_per_m3_business' => 4000,
                'subscription_fee' => 10000,
                'late_fee' => 5000,
                'due_date_day' => 20,
                'water_source_info' => 'Sumur Bor Dalam Dusun Krajan',
            ]
        );

        $customer = PamsimasCustomer::updateOrCreate(
            ['customer_number' => 'PAM-0001'],
            [
                'name' => 'Warga Pelanggan Pamsimas',
                'hamlet' => 'Dusun Krajan',
                'rt_rw' => 'RT 02 / RW 03',
                'category' => 'rumah_tangga',
                'initial_meter' => 120,
                'installation_date' => '2025-01-01',
                'status' => 'aktif',
            ]
        );

        $usage = PamsimasUsage::updateOrCreate(
            ['bill_number' => 'BILL-PAM-202610-0001'],
            [
                'customer_id' => $customer->id,
                'billing_period' => '2026-10',
                'previous_meter' => 120,
                'current_meter' => 145,
                'usage_m3' => 25,
                'rate_per_m3' => 2500,
                'water_charge' => 62500,
                'subscription_fee' => 10000,
                'late_fee' => 0,
                'total_amount' => 72500,
                'due_date' => '2026-10-20',
                'payment_status' => 'lunas',
                'paid_at' => Carbon::parse('2026-10-18 10:00:00'),
                'payment_method' => 'tunai',
                'receipt_number' => 'KW-PAM-202610-0001',
                'collected_by' => 'Hendra Setiawan',
                'reading_date' => '2026-10-15',
            ]
        );

        $transactions = [
            [
                'transaction_code' => 'BKM-'.$ticket->ticket_number,
                'unit_code' => 'cfd',
                'type' => 'income',
                'amount' => $ticket->total_amount,
                'transaction_date' => $event->event_date,
                'payment_method' => 'tunai',
                'reference_type' => 'cfd',
                'reference_id' => $ticket->id,
                'recipient_or_payer' => $vendor->name,
                'description' => 'Retribusi lapak CFD '.$ticket->ticket_number,
                'receipt_number' => $ticket->ticket_number,
                'created_by' => $bendahara->id,
            ],
            [
                'transaction_code' => 'BKM-'.$usage->receipt_number,
                'unit_code' => 'pamsimas',
                'type' => 'income',
                'amount' => $usage->total_amount,
                'transaction_date' => $usage->paid_at->toDateString(),
                'payment_method' => $usage->payment_method,
                'reference_type' => 'pamsimas',
                'reference_id' => $usage->id,
                'recipient_or_payer' => $customer->name,
                'description' => 'Pembayaran tagihan air periode '.$usage->billing_period,
                'receipt_number' => $usage->receipt_number,
                'created_by' => $bendahara->id,
            ],
            [
                'transaction_code' => 'BKK-PST-202610-001',
                'unit_code' => 'pusat',
                'type' => 'expense',
                'amount' => 650000,
                'transaction_date' => '2026-10-02',
                'payment_method' => 'tunai',
                'reference_type' => 'umum',
                'recipient_or_payer' => 'Operasional Kantor',
                'description' => 'Pembelian perlengkapan operasional BUMDes.',
                'receipt_number' => 'BKK-PST-202610-001',
                'created_by' => $bendahara->id,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::updateOrCreate(
                ['transaction_code' => $transaction['transaction_code']],
                $transaction
            );
        }
    }
}
