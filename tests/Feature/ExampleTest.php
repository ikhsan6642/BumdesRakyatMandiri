<?php

namespace Tests\Feature;

use App\Models\CfdEvent;
use App\Models\CfdTicket;
use App\Models\CfdVendor;
use App\Models\CfdZone;
use App\Models\PamsimasCustomer;
use App\Models\PamsimasUsage;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\BumdesRakyatMandiriSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BumdesRakyatMandiriSeeder::class);
    }

    /**
     * Tamu dialihkan ke halaman login.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /**
     * Halaman login dapat diakses.
     */
    public function test_login_page_renders_successfully(): void
    {
        User::factory()->create([
            'name' => 'Demo Pertanian',
            'email' => 'pertanian@bumdes.desa.id',
            'role' => 'admin_unit',
            'unit_code' => 'pertanian',
        ]);
        User::factory()->create([
            'name' => 'Demo Peternakan',
            'email' => 'peternakan@bumdes.desa.id',
            'role' => 'admin_unit',
            'unit_code' => 'peternakan',
        ]);

        $response = $this->get('/login');

        $response->assertOk()
            ->assertSee('logo-bumdes.jpeg')
            ->assertSee('id="bumdes-logo"', false)
            ->assertSee('rounded-full')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('direktur@bumdes.desa.id')
            ->assertDontSee('Masuk Cepat')
            ->assertDontSee('cfd@bumdes.desa.id')
            ->assertDontSee('pamsimas@bumdes.desa.id')
            ->assertDontSee('pertanian@bumdes.desa.id')
            ->assertDontSee('peternakan@bumdes.desa.id')
            ->assertDontSee('Demo Pertanian')
            ->assertDontSee('Demo Peternakan');

        $this->post('/login/quick', ['user_id' => 1])->assertNotFound();
    }

    /**
     * Ketua dapat mengakses dashboard utama.
     */
    public function test_direktur_can_access_dashboard(): void
    {
        $user = User::where('email', 'direktur@bumdes.desa.id')->first();

        $response = $this->actingAs($user)->get('/');
        $response->assertOk()
            ->assertSee('logo-bumdes.jpeg')
            ->assertSee('id="bumdes-logo"', false)
            ->assertSee('rounded-full')
            ->assertSee('Ketua')
            ->assertDontSee('Listrik Pompa & Pipa', false)
            ->assertDontSee('>direktur<', false);
    }

    /**
     * Admin CFD login langsung diarahkan ke halaman pribadi CFD.
     */
    public function test_admin_cfd_login_redirects_to_cfd_page(): void
    {
        $response = $this->post('/login', [
            'email' => 'cfd@bumdes.desa.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('cfd.index'));
    }

    /**
     * Admin CFD dapat membuka halaman CFD.
     */
    public function test_admin_cfd_can_access_cfd_page(): void
    {
        $user = User::where('email', 'cfd@bumdes.desa.id')->first();

        $response = $this->actingAs($user)->get('/unit/cfd');
        $response->assertOk()
            ->assertSee('lg:sticky')
            ->assertSee('lg:top-0')
            ->assertDontSee('lg:static')
            ->assertSee('Retribusi Karcis Lapak')
            ->assertSee('Karcis Lapak:')
            ->assertDontSee('Pendapatan Parkir CFD')
            ->assertDontSee('Kebersihan & Keamanan:')
            ->assertDontSee('Biaya Operasional & Sampah')
            ->assertDontSee('Laba Bersih CFD')
            ->assertDontSee('Update Parkir & Biaya')
            ->assertDontSee('Simpan Rekap Biaya');
    }

    /**
     * Admin CFD TIDAK BISA mengakses halaman Pamsimas dan dialihkan ke CFD dengan pesan error.
     */
    public function test_admin_cfd_cannot_access_pamsimas_page(): void
    {
        $user = User::where('email', 'cfd@bumdes.desa.id')->first();

        $response = $this->actingAs($user)->get('/unit/pamsimas');
        $response->assertRedirect(route('cfd.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Admin Pamsimas TIDAK BISA mengakses halaman CFD dan dialihkan ke Pamsimas dengan pesan error.
     */
    public function test_admin_pamsimas_cannot_access_cfd_page(): void
    {
        $user = User::where('email', 'pamsimas@bumdes.desa.id')->first();

        $response = $this->actingAs($user)->get('/unit/cfd');
        $response->assertRedirect(route('pamsimas.index'));
        $response->assertSessionHas('error');
    }

    public function test_pamsimas_page_hides_maintenance_cost_actions_and_section(): void
    {
        $admin = User::where('email', 'pamsimas@bumdes.desa.id')->firstOrFail();

        $this->actingAs($admin)->get(route('pamsimas.index'))
            ->assertOk()
            ->assertDontSee('Biaya Listrik Pompa & Pipa')
            ->assertDontSee('Biaya Pemeliharaan & Pompa')
            ->assertDontSee('Biaya Operasional & Pemeliharaan Pompa Pamsimas')
            ->assertDontSee('pamsimas.maintenance.store')
            ->assertDontSee('Biaya Listrik & Operasional Pompa', false)
            ->assertSee('Tagihan Rekening Air Bulanan');
    }

    public function test_pamsimas_customer_can_be_edited_and_deleted_without_billing_history(): void
    {
        $admin = User::where('email', 'pamsimas@bumdes.desa.id')->firstOrFail();
        $customer = PamsimasCustomer::create([
            'customer_number' => 'PAM-TEST-001',
            'name' => 'Warga Uji',
            'hamlet' => 'Dusun Lama',
            'rt_rw' => 'RT 01 / RW 01',
            'category' => 'rumah_tangga',
            'initial_meter' => 10,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)->get(route('pamsimas.index', ['tab' => 'customers']))
            ->assertOk()
            ->assertSee('Edit')
            ->assertSee('Hapus')
            ->assertSee('PAM-TEST-001');

        $this->put(route('pamsimas.customers.update', $customer->id), [
            'name' => 'Warga Uji Diperbarui',
            'nik' => '3201000000000001',
            'phone' => '08123456789',
            'hamlet' => 'Dusun Baru',
            'rt_rw' => 'RT 02 / RW 03',
            'address' => 'Jalan Desa',
            'category' => 'usaha',
            'initial_meter' => 12,
            'installation_date' => '2026-01-10',
            'status' => 'nonaktif',
            'notes' => 'Data telah diperbarui',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pamsimas_customers', [
            'id' => $customer->id,
            'name' => 'Warga Uji Diperbarui',
            'hamlet' => 'Dusun Baru',
            'status' => 'nonaktif',
        ]);

        $this->delete(route('pamsimas.customers.destroy', $customer->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pamsimas_customers', ['id' => $customer->id]);
    }

    public function test_pamsimas_customer_with_billing_history_cannot_be_deleted(): void
    {
        $admin = User::where('email', 'pamsimas@bumdes.desa.id')->firstOrFail();
        $usage = PamsimasUsage::firstOrFail();
        $customer = PamsimasCustomer::findOrFail($usage->customer_id);

        $this->actingAs($admin)
            ->delete(route('pamsimas.customers.destroy', $customer->id))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pamsimas_customers', ['id' => $customer->id]);
        $this->assertDatabaseHas('pamsimas_usages', ['id' => $usage->id, 'customer_id' => $customer->id]);
    }

    /**
     * Admin CFD membuka root / dialihkan otomatis ke halaman pribadi CFD.
     */
    public function test_admin_cfd_visiting_root_redirected_to_cfd(): void
    {
        $user = User::where('email', 'cfd@bumdes.desa.id')->first();

        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect(route('cfd.index'));
    }

    /**
     * Direktur dapat mengakses semua unit tanpa halangan.
     */
    public function test_direktur_can_access_remaining_units(): void
    {
        $direktur = User::where('email', 'direktur@bumdes.desa.id')->first();

        $this->actingAs($direktur)->get('/unit/cfd')->assertStatus(200);
        $this->actingAs($direktur)->get('/unit/pamsimas')->assertStatus(200);
        $this->actingAs($direktur)->get('/unit/pertanian')->assertNotFound();
        $this->actingAs($direktur)->get('/unit/peternakan')->assertNotFound();
    }

    /**
     * Halaman utama seluruh modul tetap dapat dibuka oleh direktur.
     */
    public function test_direktur_can_access_all_finance_pages(): void
    {
        $direktur = User::where('email', 'direktur@bumdes.desa.id')->first();

        $this->actingAs($direktur)->get(route('finance.transactions'))
            ->assertOk()
            ->assertSee('id="filter-unit-code"', false);
        $this->get(route('finance.reports'))
            ->assertOk()
            ->assertDontSee('Perbandingan Unit Usaha')
            ->assertDontSee('unitBreakdowns')
            ->assertSee('Divisi / Unit Usaha')
            ->assertSee('unit_code')
            ->assertSee('report-week')
            ->assertSee('Unit Usaha CFD &amp; Pasar UMKM Minggu', false)
            ->assertSee('Unit Usaha Pamsimas Tirta Mandiri')
            ->assertDontSee('<option value="pusat"', false)
            ->assertSee('LAPORAN LABA / RUGI KONSOLIDASI')
            ->assertDontSee('2. Laporan Arus Kas')
            ->assertDontSee('3. Neraca Sederhana')
            ->assertDontSee('LAPORAN ARUS KAS')
            ->assertDontSee('NERACA KEUANGAN SEDERHANA')
            ->assertSee('LAPORAN LABA / RUGI KONSOLIDASI');
        $this->assertStringContainsString('Ketua BUMDes "Rakyat Mandiri"', $this->get(route('finance.reports'))->getContent());
        $this->assertStringContainsString('PEMERINTAH DESA CINTARAKYAT', $this->get(route('finance.reports'))->getContent());
        $this->assertStringContainsString('KECAMATAN SAMARANG', $this->get(route('finance.reports'))->getContent());
        $this->assertStringContainsString('KABUPATEN GARUT', $this->get(route('finance.reports'))->getContent());
        $this->assertStringContainsString('Dede Sandi', $this->get(route('finance.reports'))->getContent());
        $this->assertStringNotContainsString('NIP. 2022.01.001', $this->get(route('finance.reports'))->getContent());
        $this->assertStringNotContainsString('Bendahara BUMDes "Rakyat Mandiri"', $this->get(route('finance.reports'))->getContent());
        $this->assertStringNotContainsString('NIP. 2022.01.002', $this->get(route('finance.reports'))->getContent());
        $this->get('/keuangan/akun')->assertNotFound();
        $this->get('/keuangan/bagi-hasil')->assertNotFound();
    }

    public function test_management_can_filter_finance_report_by_unit(): void
    {
        $direktur = User::where('email', 'direktur@bumdes.desa.id')->firstOrFail();

        foreach (['cfd', 'pamsimas'] as $unitCode) {
            Transaction::create([
                'transaction_code' => 'BKM-REPORT-'.$unitCode,
                'unit_code' => $unitCode,
                'type' => 'income',
                'amount' => $unitCode === 'cfd' ? 123456 : 789012,
                'transaction_date' => '2026-06-15',
                'payment_method' => 'tunai',
                'recipient_or_payer' => 'Pengujian Laporan',
                'description' => 'Transaksi uji laporan '.$unitCode,
                'receipt_number' => 'BKM-REPORT-'.$unitCode,
                'created_by' => $direktur->id,
            ]);
        }

        $this->actingAs($direktur)
            ->get(route('finance.reports', ['year' => 2026, 'unit_code' => 'cfd']))
            ->assertOk()
            ->assertSee('Unit Usaha CFD &amp; Pasar UMKM Minggu', false)
            ->assertSee('Divisi / Unit Usaha')
            ->assertSee('unit_code')
            ->assertSee('145.456')
            ->assertDontSee('789.012')
            ->assertSee('LAPORAN LABA / RUGI UNIT USAHA CFD &amp; PASAR UMKM MINGGU', false);
    }

    public function test_unit_admin_finance_report_stays_scoped_to_their_unit(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();

        Transaction::create([
            'transaction_code' => 'BKM-REPORT-ADMIN-CFD',
            'unit_code' => 'cfd',
            'type' => 'income',
            'amount' => 100000,
            'transaction_date' => '2026-06-15',
            'payment_method' => 'tunai',
            'recipient_or_payer' => 'Pengujian Laporan CFD',
            'description' => 'Transaksi uji laporan admin CFD',
            'receipt_number' => 'BKM-REPORT-ADMIN-CFD',
            'created_by' => $admin->id,
        ]);
        Transaction::create([
            'transaction_code' => 'BKM-REPORT-ADMIN-PAM',
            'unit_code' => 'pamsimas',
            'type' => 'income',
            'amount' => 100000,
            'transaction_date' => '2026-06-15',
            'payment_method' => 'tunai',
            'recipient_or_payer' => 'Pengujian Laporan Pamsimas',
            'description' => 'Transaksi uji laporan admin Pamsimas',
            'receipt_number' => 'BKM-REPORT-ADMIN-PAM',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('finance.reports', ['year' => 2026, 'unit_code' => 'pamsimas']))
            ->assertOk()
            ->assertSee('Unit Usaha CFD &amp; Pasar UMKM Minggu', false)
            ->assertDontSee('Unit Usaha Pamsimas Tirta Mandiri')
            ->assertDontSee('Divisi / Unit Usaha')
            ->assertDontSee('name="unit_code"');
    }

    public function test_pamsimas_finance_report_uses_month_filter_instead_of_week_filter(): void
    {
        $admin = User::where('email', 'pamsimas@bumdes.desa.id')->firstOrFail();
        foreach ([
            ['BKM-PAM-MONTH-001', '2026-10-20', 112233],
            ['BKM-PAM-MONTH-002', '2026-10-27', 223344],
            ['BKM-PAM-MONTH-003', '2026-11-02', 334455],
        ] as [$code, $date, $amount]) {
            Transaction::create([
                'transaction_code' => $code,
                'unit_code' => 'pamsimas',
                'type' => 'income',
                'amount' => $amount,
                'transaction_date' => $date,
                'payment_method' => 'tunai',
                'recipient_or_payer' => 'Uji Rekap Bulanan Pamsimas',
                'description' => 'Transaksi uji periode bulanan.',
                'receipt_number' => $code,
                'created_by' => $admin->id,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('finance.reports', [
                'year' => 2026,
                'month' => 10,
                'week' => '2026-W43',
            ]))
            ->assertOk()
            ->assertDontSee('report-week')
            ->assertSee('408.077')
            ->assertDontSee('334.455')
            ->assertSee('Bulan 10 Tahun Anggaran 2026');
    }

    /**
     * Migrasi menghapus fitur, tabel, dan data yang dipilih tanpa menghapus transaksi lain.
     */
    public function test_removed_features_and_data_are_absent(): void
    {
        foreach ([
            'accounts',
            'profit_distributions',
            'agriculture_cycles',
            'agriculture_productions',
            'agriculture_sales',
            'agriculture_machineries',
            'agriculture_rentals',
            'livestock_categories',
            'livestock_batches',
            'livestock_operations',
            'livestock_sales',
        ] as $table) {
            $this->assertFalse(Schema::hasTable($table), "Expected {$table} to be removed.");
        }

        $this->assertFalse(Schema::hasColumn('transactions', 'account_id'));
        $this->assertDatabaseMissing('business_units', ['code' => 'pertanian']);
        $this->assertDatabaseMissing('business_units', ['code' => 'peternakan']);
        $this->assertDatabaseMissing('users', ['email' => 'pertanian@bumdes.desa.id']);
        $this->assertDatabaseMissing('users', ['email' => 'peternakan@bumdes.desa.id']);
        $this->assertDatabaseMissing('transactions', ['unit_code' => 'pertanian']);
        $this->assertDatabaseMissing('transactions', ['unit_code' => 'peternakan']);
        $this->assertDatabaseHas('transactions', ['unit_code' => 'cfd']);
        $this->assertDatabaseHas('transactions', ['unit_code' => 'pamsimas']);
    }

    /**
     * Halaman Buku Kas admin unit dapat dibuka dan difilter tanpa error.
     */
    public function test_admin_unit_can_open_and_filter_its_cash_book(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->first();

        $this->actingAs($admin)->get(route('finance.transactions'))
            ->assertOk()
            ->assertDontSee('id="filter-unit-code"', false);
        $this->get(route('finance.transactions', [
            'unit_code' => 'pamsimas',
            'type' => 'income',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'search' => 'CFD',
        ]))->assertOk()->assertDontSee('id="filter-unit-code"', false);
        $this->get(route('finance.reports'))->assertOk();

        $pamsimasAdmin = User::where('email', 'pamsimas@bumdes.desa.id')->firstOrFail();
        $this->actingAs($pamsimasAdmin)->get(route('finance.transactions'))
            ->assertOk()
            ->assertDontSee('id="filter-unit-code"', false);
    }

    /**
     * Tampilan cetak transaksi dan tagihan tetap tersedia, cetak karcis CFD dihapus.
     */
    public function test_remaining_print_pages_render_and_cfd_ticket_print_is_removed(): void
    {
        $direktur = User::where('email', 'direktur@bumdes.desa.id')->first();
        $this->actingAs($direktur);

        $this->get(route('finance.transactions.print', Transaction::firstOrFail()->id))
            ->assertOk()
            ->assertSee('logo-bumdes.jpeg')
            ->assertSee('Desa Cintarakyat, Kecamatan Samarang, Kabupaten Garut')
            ->assertSee('Ketua BUMDes')
            ->assertSee('Dede Sandi')
            ->assertSee('Pihak Terkait')
            ->assertDontSee('Bendahara')
            ->assertDontSee('Joko Supriyono')
            ->assertDontSee('Siti Aminah')
            ->assertDontSee('Direktur BUMDes');
        $this->get('/unit/cfd/karcis/'.CfdTicket::firstOrFail()->id.'/cetak')->assertNotFound();
        $this->get(route('pamsimas.bills.print', PamsimasUsage::firstOrFail()->id))->assertOk();
    }

    /**
     * Kas masuk dan keluar dapat disimpan tanpa nomor bukti manual.
     */
    public function test_income_and_expense_transactions_can_be_saved_without_receipt_number(): void
    {
        $direktur = User::where('email', 'direktur@bumdes.desa.id')->firstOrFail();
        $this->actingAs($direktur);

        foreach (['income', 'expense'] as $type) {
            $response = $this->post(route('finance.transactions.store'), [
                'unit_code' => 'pusat',
                'type' => $type,
                'amount' => 150000,
                'transaction_date' => '2026-10-20',
                'payment_method' => 'tunai',
                'recipient_or_payer' => 'Warga Desa',
                'description' => 'Transaksi uji '.$type,
            ]);

            $prefix = $type === 'income' ? 'BKM' : 'BKK';
            $transaction = Transaction::where('type', $type)->latest('id')->firstOrFail();

            $response->assertRedirect();
            $response->assertSessionHas('success');
            $this->assertMatchesRegularExpression('/^'.$prefix.'-202610-\d{3}$/', $transaction->transaction_code);
            $this->assertSame($transaction->transaction_code, $transaction->receipt_number);
        }
    }

    /**
     * Karcis dapat disimpan dengan field yang benar-benar dikirim form.
     */
    public function test_cfd_ticket_can_be_saved_without_collector_name(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $event = CfdEvent::firstOrFail();
        $zone = CfdZone::firstOrFail();

        $response = $this->actingAs($admin)->post(route('cfd.tickets.store'), [
            'event_id' => $event->id,
            'vendor_name_manual' => 'Pedagang Uji',
            'zone_id' => $zone->id,
            'spot_number' => 'A-99',
            'fee_amount' => 20000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 2000,
            'payment_status' => 'lunas',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $ticket = CfdTicket::where('spot_number', 'A-99')->firstOrFail();
        $this->assertSame($admin->name, $ticket->collected_by);
        $this->assertSame((float) $zone->default_fee, (float) $ticket->fee_amount);
        $this->assertSame(22000.0, (float) $ticket->total_amount);
        $this->assertDatabaseHas('transactions', [
            'transaction_code' => 'BKM-'.$ticket->ticket_number,
            'reference_id' => $ticket->id,
            'amount' => 22000,
        ]);
    }

    public function test_cfd_ticket_number_is_unique_across_events_on_the_same_date(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $zone = CfdZone::firstOrFail();
        $firstEvent = CfdEvent::create([
            'event_code' => 'CFD-TICKET-NUMBER-001',
            'name' => 'CFD Uji Nomor Pertama',
            'event_date' => '2026-10-20',
            'status' => 'terjadwal',
        ]);
        $secondEvent = CfdEvent::create([
            'event_code' => 'CFD-TICKET-NUMBER-002',
            'name' => 'CFD Uji Nomor Kedua',
            'event_date' => '2026-10-20',
            'status' => 'terjadwal',
        ]);

        CfdTicket::create([
            'event_id' => $firstEvent->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-20261020-001',
            'spot_number' => 'TEST-01',
            'fee_amount' => 20000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 20000,
            'payment_status' => 'belum_bayar',
        ]);

        $this->actingAs($admin)->post(route('cfd.tickets.store'), [
            'event_id' => $secondEvent->id,
            'vendor_name_manual' => 'Pedagang Uji Nomor',
            'zone_id' => $zone->id,
            'spot_number' => 'TEST-02',
            'payment_status' => 'lunas',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('cfd_tickets', [
            'event_id' => $secondEvent->id,
            'ticket_number' => 'KRC-20261020-002',
        ]);
    }

    public function test_deleting_cash_book_ticket_transaction_updates_event_recap(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $event = CfdEvent::create([
            'event_code' => 'CFD-DELETE-TX-TEST',
            'name' => 'CFD Uji Hapus Transaksi Karcis',
            'event_date' => '2026-11-22',
            'status' => 'terjadwal',
        ]);
        $zone = CfdZone::firstOrFail();
        $ticket = CfdTicket::create([
            'event_id' => $event->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-DELETE-TX-001',
            'spot_number' => 'DELETE-01',
            'fee_amount' => 30000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 30000,
            'payment_status' => 'lunas',
        ]);
        $transaction = Transaction::create([
            'transaction_code' => 'BKM-KRC-DELETE-TX-001',
            'unit_code' => 'cfd',
            'type' => 'income',
            'amount' => 30000,
            'transaction_date' => $event->event_date,
            'payment_method' => 'tunai',
            'reference_type' => 'cfd',
            'reference_id' => $ticket->id,
            'recipient_or_payer' => 'Pedagang Uji',
            'description' => 'Karcis untuk pengujian penghapusan.',
            'receipt_number' => $ticket->ticket_number,
            'created_by' => $admin->id,
        ]);
        $event->calculateNetRevenue();
        $this->assertSame(30000.0, (float) $event->fresh()->total_stall_revenue);

        $this->actingAs($admin)
            ->delete(route('finance.transactions.destroy', $transaction->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cfd_tickets', ['id' => $ticket->id]);
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertSame(0.0, (float) $event->fresh()->total_stall_revenue);
        $this->assertSame(0.0, (float) $event->fresh()->net_revenue);
        $this->assertFalse(CfdTicket::successfullyRecorded()->whereKey($ticket->id)->exists());
    }

    public function test_failed_ticket_ledger_write_does_not_appear_in_ticket_recap(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $event = CfdEvent::firstOrFail();
        $zone = CfdZone::firstOrFail();
        $orphan = CfdTicket::create([
            'event_id' => $event->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-ORPHAN-001',
            'spot_number' => 'A-97',
            'fee_amount' => 20000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 20000,
            'payment_status' => 'lunas',
        ]);
        $legacyRecordedTicket = CfdTicket::create([
            'event_id' => $event->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-LEGACY-001',
            'spot_number' => 'A-96',
            'fee_amount' => 15000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 15000,
            'payment_status' => 'lunas',
        ]);
        Transaction::create([
            'transaction_code' => 'BKM-'.$legacyRecordedTicket->ticket_number,
            'unit_code' => 'cfd',
            'type' => 'income',
            'amount' => $legacyRecordedTicket->total_amount,
            'transaction_date' => $event->event_date,
            'payment_method' => 'tunai',
            'recipient_or_payer' => 'Pedagang Lama',
            'description' => 'Karcis lama yang sudah tercatat.',
            'receipt_number' => $legacyRecordedTicket->ticket_number,
            'created_by' => $admin->id,
        ]);

        DB::unprepared("
            CREATE TRIGGER fail_cfd_transaction
            BEFORE INSERT ON transactions
            WHEN NEW.reference_type = 'cfd'
            BEGIN
                SELECT RAISE(ABORT, 'Simulated CFD ledger failure');
            END
        ");

        try {
            $this->withoutExceptionHandling();

            try {
                $this->actingAs($admin)->post(route('cfd.tickets.store'), [
                    'event_id' => $event->id,
                    'vendor_name_manual' => 'Pedagang Gagal',
                    'zone_id' => $zone->id,
                    'spot_number' => 'A-98',
                    'fee_amount' => 20000,
                    'payment_status' => 'lunas',
                ]);
                $this->fail('Expected the simulated ledger write to fail.');
            } catch (QueryException $exception) {
                $this->assertStringContainsString('Simulated CFD ledger failure', $exception->getMessage());
            }
        } finally {
            DB::unprepared('DROP TRIGGER IF EXISTS fail_cfd_transaction');
        }

        $this->assertDatabaseMissing('cfd_tickets', ['spot_number' => 'A-98']);
        $this->assertDatabaseHas('cfd_tickets', ['id' => $orphan->id]);
        $this->assertFalse(CfdTicket::successfullyRecorded()->whereKey($orphan->id)->exists());
        $this->actingAs($admin)->get(route('cfd.index'))
            ->assertOk()
            ->assertSee('KRC-LEGACY-001')
            ->assertDontSee('KRC-ORPHAN-001')
            ->assertDontSee('Pedagang Gagal');
    }

    public function test_vendor_registration_shows_and_reserves_an_available_spot(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $zone = CfdZone::firstOrFail();

        $this->actingAs($admin)->get(route('cfd.index'))
            ->assertOk()
            ->assertSee('A-02')
            ->assertSee('49 lapak kosong');

        $this->post(route('cfd.vendors.store'), [
            'name' => 'UMKM Lapak Uji',
            'owner_name' => 'Pemilik Uji',
            'preferred_zone_id' => $zone->id,
            'spot_number' => 'A-02',
            'category' => 'kuliner_makanan',
            'origin_village' => 'Desa Rakyat Mandiri',
            'status' => 'aktif',
        ])->assertSessionHasNoErrors();

        $vendor = CfdVendor::where('name', 'UMKM Lapak Uji')->firstOrFail();
        $this->assertSame('A-02', $vendor->spot_number);

        $this->post(route('cfd.vendors.store'), [
            'name' => 'UMKM Lapak Duplikat',
            'owner_name' => 'Pemilik Duplikat',
            'preferred_zone_id' => $zone->id,
            'spot_number' => 'A-02',
            'category' => 'kuliner_makanan',
            'origin_village' => 'Desa Rakyat Mandiri',
            'status' => 'aktif',
        ])->assertSessionHasErrors('spot_number');
    }

    public function test_vendor_directory_allows_editing_all_vendor_details_and_validates_spot(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $vendor = CfdVendor::where('vendor_code', 'CFD-V-001')->firstOrFail();
        $zone = CfdZone::findOrFail($vendor->preferred_zone_id);

        $this->actingAs($admin)->get(route('cfd.index', ['tab' => 'vendors']))
            ->assertOk()
            ->assertSee('Edit Data Pedagang UMKM')
            ->assertSee('Simpan Perubahan')
            ->assertSee('available_spots_by_zone')
            ->assertSee('Hapus')
            ->assertSee(route('cfd.vendors.destroy', $vendor->id))
            ->assertSee('A-01');

        $this->put(route('cfd.vendors.update', $vendor->id), [
            'name' => 'Warung UMKM Diperbarui',
            'owner_name' => 'Pemilik Diperbarui',
            'phone' => '081234567899',
            'preferred_zone_id' => $zone->id,
            'spot_number' => $vendor->spot_number,
            'category' => 'kuliner_minuman',
            'origin_village' => 'Desa Tetangga',
            'status' => 'aktif',
            'notes' => 'Data diperbarui',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('cfd_vendors', [
            'id' => $vendor->id,
            'name' => 'Warung UMKM Diperbarui',
            'owner_name' => 'Pemilik Diperbarui',
            'phone' => '081234567899',
            'preferred_zone_id' => $zone->id,
            'spot_number' => $vendor->spot_number,
            'category' => 'kuliner_minuman',
            'origin_village' => 'Desa Tetangga',
            'status' => 'aktif',
            'notes' => 'Data diperbarui',
        ]);

        CfdVendor::create([
            'vendor_code' => 'CFD-V-TEST',
            'name' => 'Pedagang Slot Terisi',
            'owner_name' => 'Pemilik Slot',
            'preferred_zone_id' => $zone->id,
            'spot_number' => 'A-02',
            'category' => 'kuliner_makanan',
            'origin_village' => 'Desa Rakyat Mandiri',
            'status' => 'aktif',
        ]);

        $this->put(route('cfd.vendors.update', $vendor->id), [
            'name' => 'Warung UMKM Diperbarui',
            'owner_name' => 'Pemilik Diperbarui',
            'preferred_zone_id' => $zone->id,
            'spot_number' => 'A-02',
            'category' => 'kuliner_minuman',
            'origin_village' => 'Desa Tetangga',
            'status' => 'aktif',
        ])->assertSessionHasErrors('spot_number');
    }

    public function test_vendor_can_be_deleted_without_deleting_ticket_history(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $event = CfdEvent::firstOrFail();
        $zone = CfdZone::firstOrFail();
        $vendor = CfdVendor::where('vendor_code', 'CFD-V-001')->firstOrFail();
        $ticket = CfdTicket::create([
            'event_id' => $event->id,
            'vendor_id' => $vendor->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-VENDOR-DELETE-001',
            'spot_number' => $vendor->spot_number,
            'fee_amount' => 20000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 20000,
            'payment_status' => 'lunas',
        ]);

        $this->actingAs($admin)
            ->delete(route('cfd.vendors.destroy', $vendor->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('cfd_vendors', ['id' => $vendor->id]);
        $this->assertDatabaseHas('cfd_tickets', [
            'id' => $ticket->id,
            'vendor_id' => null,
            'ticket_number' => $ticket->ticket_number,
        ]);
    }

    public function test_registered_vendor_ticket_uses_assigned_zone_and_spot(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $vendor = CfdVendor::where('vendor_code', 'CFD-V-001')->firstOrFail();
        $zone = CfdZone::where('id', '!=', $vendor->preferred_zone_id)->create([
            'name' => 'Zona Uji',
            'code' => 'Z-B',
            'default_fee' => 25000,
            'capacity_spots' => 20,
        ]);
        $event = CfdEvent::create([
            'event_code' => 'CFD-2026-W44',
            'name' => 'CFD Uji Tiket Pedagang',
            'event_date' => '2026-11-01',
            'status' => 'terjadwal',
        ]);

        $this->actingAs($admin)->post(route('cfd.tickets.store'), [
            'event_id' => $event->id,
            'vendor_id' => $vendor->id,
            'zone_id' => $zone->id,
            'spot_number' => 'B-99',
            'fee_amount' => 1,
            'payment_status' => 'lunas',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('cfd_tickets', [
            'event_id' => $event->id,
            'vendor_id' => $vendor->id,
            'zone_id' => $vendor->preferred_zone_id,
            'spot_number' => $vendor->spot_number,
            'fee_amount' => CfdZone::findOrFail($vendor->preferred_zone_id)->default_fee,
        ]);
    }

    public function test_ticket_form_marks_registered_vendors_saved_for_selected_event(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $vendor = CfdVendor::where('vendor_code', 'CFD-V-001')->firstOrFail();
        $event = CfdEvent::create([
            'event_code' => 'CFD-STATUS-TEST-001',
            'name' => 'CFD Uji Status Karcis',
            'event_date' => '2026-11-15',
            'status' => 'terjadwal',
        ]);
        $zone = CfdZone::findOrFail($vendor->preferred_zone_id);

        $this->actingAs($admin)->post(route('cfd.tickets.store'), [
            'event_id' => $event->id,
            'vendor_id' => $vendor->id,
            'zone_id' => $zone->id,
            'spot_number' => $vendor->spot_number,
            'payment_status' => 'lunas',
        ])->assertSessionHasNoErrors();

        $this->get(route('cfd.index'))
            ->assertOk()
            ->assertSee('ticketedVendorIdsByEvent')
            ->assertSee('ticketVendorLabel')
            ->assertSee('SUDAH DISIMPAN')
            ->assertSee('BELUM DIINPUT');
    }

    public function test_finance_report_can_be_filtered_by_week_for_unit_admin(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        foreach ([
            ['BKM-WEEK-CFD-001', 'cfd', '2026-10-20', 55123],
            ['BKM-WEEK-CFD-002', 'cfd', '2026-10-26', 83456],
            ['BKM-WEEK-PAM-001', 'pamsimas', '2026-10-20', 99234],
        ] as [$code, $unitCode, $date, $amount]) {
            Transaction::create([
                'transaction_code' => $code,
                'unit_code' => $unitCode,
                'type' => 'income',
                'amount' => $amount,
                'transaction_date' => $date,
                'payment_method' => 'tunai',
                'recipient_or_payer' => 'Uji Laporan Mingguan',
                'description' => 'Transaksi uji filter laporan mingguan.',
                'receipt_number' => $code,
                'created_by' => $admin->id,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('finance.reports', ['week' => '2026-W43', 'unit_code' => 'pamsimas']))
            ->assertOk()
            ->assertSee('55.123')
            ->assertDontSee('83.456')
            ->assertDontSee('99.234')
            ->assertSee('2026-W43');
    }

    public function test_cfd_page_does_not_show_weekly_finance_filter(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();

        $this->actingAs($admin)->get(route('cfd.index'))
            ->assertOk()
            ->assertDontSee('Filter Rekap Mingguan');
    }

    public function test_zones_can_be_edited_and_deleted_without_losing_vendor_assignments(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $zone = CfdZone::firstOrFail();
        $vendor = CfdVendor::where('preferred_zone_id', $zone->id)->firstOrFail();
        $originalSpot = $vendor->spot_number;

        $this->actingAs($admin)->get(route('cfd.index', ['tab' => 'zones']))
            ->assertOk()
            ->assertSee('Edit Zona & Tarif Lapak', false)
            ->assertSee('Hapus');

        $this->put(route('cfd.zones.update', $zone->id), [
            'name' => 'Zona Lapak Diperbarui',
            'code' => 'Z-EDIT',
            'default_fee' => 27500,
            'capacity_spots' => 100,
            'description' => 'Keterangan zona baru',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('cfd_zones', [
            'id' => $zone->id,
            'name' => 'Zona Lapak Diperbarui',
            'code' => 'Z-EDIT',
            'default_fee' => 27500,
            'capacity_spots' => 100,
            'description' => 'Keterangan zona baru',
        ]);
        $vendor->refresh();
        $this->assertNotSame($originalSpot, $vendor->spot_number);
        $this->assertMatchesRegularExpression('/^EDIT-\d+$/', $vendor->spot_number);

        $emptyZone = CfdZone::create([
            'name' => 'Zona Kosong Uji',
            'code' => 'Z-DEL',
            'default_fee' => 10000,
            'capacity_spots' => 10,
        ]);
        $this->delete(route('cfd.zones.destroy', $emptyZone->id))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('cfd_zones', ['id' => $emptyZone->id]);
    }

    public function test_zone_with_vendor_or_ticket_history_cannot_be_deleted(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $zone = CfdZone::firstOrFail();
        $event = CfdEvent::firstOrFail();

        CfdTicket::create([
            'event_id' => $event->id,
            'zone_id' => $zone->id,
            'ticket_number' => 'KRC-ZONE-DELETE-GUARD-001',
            'spot_number' => 'HIST-01',
            'fee_amount' => 20000,
            'electricity_fee' => 0,
            'cleanliness_fee' => 0,
            'total_amount' => 20000,
            'payment_status' => 'belum_bayar',
        ]);

        $this->actingAs($admin)
            ->delete(route('cfd.zones.destroy', $zone->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('cfd_zones', ['id' => $zone->id]);
        $this->assertDatabaseHas('cfd_tickets', ['ticket_number' => 'KRC-ZONE-DELETE-GUARD-001']);
    }

    public function test_cfd_event_allows_any_weekday_and_finishes_after_collection_time(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        Carbon::setTestNow(Carbon::parse('2026-10-20 08:00:00', 'Asia/Jakarta'));

        try {
            $this->actingAs($admin)->post(route('cfd.events.store'), [
                'name' => 'CFD Hari Kerja',
                'event_date' => '2026-11-02',
                'collection_start_time' => '07:00',
                'collection_end_time' => '11:00',
            ])->assertSessionHasNoErrors();

            $weekdayEvent = CfdEvent::where('name', 'CFD Hari Kerja')->firstOrFail();
            $this->assertSame(Carbon::MONDAY, $weekdayEvent->event_date->dayOfWeek);
            $this->assertSame('terjadwal', $weekdayEvent->status);

            $this->post(route('cfd.events.store'), [
                'name' => 'CFD Uji Jadwal',
                'event_date' => '2026-10-25',
                'collection_start_time' => '07:00',
                'collection_end_time' => '06:00',
            ])->assertSessionHasErrors('collection_end_time');

            CfdEvent::create([
                'event_code' => 'CFD-2026-W43',
                'name' => 'Event Lama Pekan 43',
                'event_date' => '2026-10-18',
                'status' => 'selesai',
            ]);

            $this->post(route('cfd.events.store'), [
                'name' => 'CFD Uji Jadwal',
                'event_date' => '2026-10-25',
                'collection_start_time' => '07:00',
                'collection_end_time' => '11:00',
                'parking_revenue' => 350000,
                'cleanliness_cost' => 100000,
            ])->assertSessionHasNoErrors();

            $event = CfdEvent::where('name', 'CFD Uji Jadwal')->firstOrFail();
            $this->assertSame('CFD-2026-W43-2', $event->event_code);
            $this->assertSame('terjadwal', $event->status);
            $this->assertSame('07:00', $event->collection_start_time);
            $this->assertSame('11:00', $event->collection_end_time);

            $this->put(route('cfd.events.update_costs', $event->id), [
                'parking_revenue' => 350000,
                'cleanliness_cost' => 100000,
                'security_cost' => 0,
                'sound_electricity_cost' => 0,
            ])->assertSessionHasNoErrors();

            Carbon::setTestNow(Carbon::parse('2026-10-25 10:00:00', 'Asia/Jakarta'));
            $this->get(route('cfd.index'))->assertOk()->assertSee('Minggu')->assertSee('07:00');
            $this->assertDatabaseHas('cfd_events', [
                'id' => $event->id,
                'status' => 'berlangsung',
            ]);

            Carbon::setTestNow(Carbon::parse('2026-10-25 11:00:00', 'Asia/Jakarta'));
            $this->get(route('cfd.index'))->assertOk();

            $this->assertDatabaseHas('cfd_events', [
                'id' => $event->id,
                'status' => 'selesai',
            ]);
            $this->assertDatabaseHas('transactions', [
                'transaction_code' => sprintf('BKM-CFD-P202610-%02d', $event->id),
                'amount' => 350000,
            ]);
            $this->assertDatabaseHas('transactions', [
                'transaction_code' => sprintf('BKK-CFD-OPS202610-%02d', $event->id),
                'amount' => 100000,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_deleting_cfd_event_removes_tickets_and_related_transactions(): void
    {
        $admin = User::where('email', 'cfd@bumdes.desa.id')->firstOrFail();
        $event = CfdEvent::firstOrFail();
        $ticket = CfdTicket::firstOrFail();
        $ticketTransaction = Transaction::where('transaction_code', 'BKM-'.$ticket->ticket_number)->firstOrFail();
        $unrelatedTransaction = Transaction::where('unit_code', 'pamsimas')->firstOrFail();

        $response = $this->actingAs($admin)->delete(route('cfd.events.destroy', $event->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('cfd_events', ['id' => $event->id]);
        $this->assertDatabaseMissing('cfd_tickets', ['event_id' => $event->id]);
        $this->assertDatabaseMissing('transactions', ['id' => $ticketTransaction->id]);
        $this->assertDatabaseHas('transactions', ['id' => $unrelatedTransaction->id]);
    }
}
