<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Gamifikasi;
use App\Models\Nasabah;
use App\Models\Pengepul;
use App\Models\Sampah;
use App\Models\SetoranPengepul;
use App\Models\Tabungan;
use App\Models\TransaksiPengepul;
use App\Models\TransaksiTarik;
use App\Models\Hadiah;
use App\Models\PenukaranReward;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SistemKeuanganBankSampahTest extends TestCase
{
    use DatabaseTransactions;

    protected $nasabah;
    protected $pengepul;
    protected $admin;
    protected $sampah;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat Sampah
        $this->sampah = Sampah::create([
            'sampah_name' => 'Plastik Premium',
            'sampah_jenis' => 'Anorganik',
            'sampah_satuan' => 'kg',
            'sampah_harga_kg' => 2000,
            'sampah_harga_pasar' => 3000,
            'sampah_keterangan' => 'Plastik kualitas tinggi',
        ]);

        // 2. Buat Pengepul
        $this->pengepul = Pengepul::create([
            'nama' => 'Pengepul Berjaya',
            'alamat' => 'Jl. Pengepul No. 5',
            'telepon' => '081234567890',
            'username' => 'pengepultest_' . uniqid(),
            'password' => bcrypt('password123'),
            'komisi_persen' => 50,
            'status_aktif' => true,
        ]);

        // 3. Buat Nasabah
        $this->nasabah = Nasabah::create([
            'nasabah_nama' => 'Budi Eco',
            'nasabah_nik' => '1234567890123456',
            'nasabah_alamat' => 'Jl. Hijau Lestari No. 1',
            'nasabah_telepon' => '089876543210',
            'nasabah_email' => 'budi@eco.com',
            'nasabah_username' => 'budieco_' . uniqid(),
            'nasabah_password' => bcrypt('password123'),
            'nasabah_saldo' => 0,
            'nasabah_tgl_daftar' => now()->toDateString(),
            'nasabah_status' => 'aktif',
        ]);

        // Buat Tabungan & Gamifikasi Nasabah
        Tabungan::create([
            'id_nasabah' => $this->nasabah->id_nasabah,
            'tabungan_no_rekening' => 'BS-TEST-' . rand(1000, 9999),
            'tabungan_total_setor' => 0,
            'tabungan_total_tarik' => 0,
            'tabungan_saldo_akhir' => 0,
            'tabungan_tgl_update' => now(),
        ]);

        Gamifikasi::create([
            'id_nasabah' => $this->nasabah->id_nasabah,
            'poin_diperoleh' => 0,
            'total_poin' => 0,
            'level_nasabah' => 'Pemula',
            'badge' => 'Eco Starter',
            'tanggal_update' => now(),
        ]);

        // 4. Buat Admin
        $this->admin = Admin::create([
            'admin_nama' => 'Super Admin',
            'admin_username' => 'admintest_' . uniqid(),
            'admin_password' => bcrypt('password123'),
            'admin_level' => 'admin',
            'admin_status' => 'aktif',
        ]);
    }

    /**
     * Test Pengepul menimbang sampah nasabah & perhitungan finansial 50/50 komisi.
     */
    public function test_pengepul_can_setor_sampah_and_finances_are_calculated_correctly()
    {
        $payload = [
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah' => $this->sampah->id_sampah,
            'berat_kg' => 10,
            'keterangan' => 'Timbangan tes 10kg plastik',
        ];

        // Jalankan POST request sebagai Pengepul (dengan mock session)
        $response = $this->withSession([
            'user_id' => $this->pengepul->id,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.setor'), $payload);

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        // Nilai Beli = 2000 * 10 = Rp 20.000
        // Nilai Pasar = 3000 * 10 = Rp 30.000
        // Selisih = 30.000 - 20.000 = Rp 10.000
        // Komisi Pengepul (50%) = Rp 5.000
        // Bagian Admin (50%) = Rp 5.000

        // Verifikasi database TransaksiPengepul
        $this->assertDatabaseHas('transaksi_pengepul', [
            'pengepul_id' => $this->pengepul->id,
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah' => $this->sampah->id_sampah,
            'berat_kg' => 10,
            'harga_beli_kg' => 2000,
            'harga_pasar_kg' => 3000,
            'nilai_idr' => 20000,
            'selisih_total' => 10000,
            'komisi_pengepul' => 5000,
            'bagian_admin' => 5000,
            'sudah_disetor' => false,
        ]);

        // Verifikasi saldo Nasabah diupdate sebesar Nilai Beli
        $this->nasabah->refresh();
        $this->assertEquals(20000, $this->nasabah->nasabah_saldo);

        // Verifikasi tabungan nasabah diupdate
        $tabungan = Tabungan::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $this->assertEquals(20000, $tabungan->tabungan_saldo_akhir);
        $this->assertEquals(20000, $tabungan->tabungan_total_setor);

        // Verifikasi gamifikasi nasabah diupdate (10 kg * 10 = 100 poin)
        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $this->assertEquals(100, $gamifikasi->total_poin);
        $this->assertEquals('Aktif', $gamifikasi->level_nasabah);
        $this->assertEquals('Penyetor Konsisten', $gamifikasi->badge);
    }

    /**
     * Test Pengepul membuat batch setoran ke admin.
     */
    public function test_pengepul_can_create_setoran_to_admin()
    {
        // Buat 2 transaksi belum disetor
        $trx1 = TransaksiPengepul::create([
            'pengepul_id' => $this->pengepul->id,
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah' => $this->sampah->id_sampah,
            'berat_kg' => 5,
            'harga_beli_kg' => 2000,
            'harga_pasar_kg' => 3000,
            'nilai_idr' => 10000,
            'selisih_total' => 5000,
            'komisi_pengepul' => 2500,
            'bagian_admin' => 2500,
            'sudah_disetor' => false,
            'tanggal' => now()->toDateString(),
        ]);

        $trx2 = TransaksiPengepul::create([
            'pengepul_id' => $this->pengepul->id,
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah' => $this->sampah->id_sampah,
            'berat_kg' => 8,
            'harga_beli_kg' => 2000,
            'harga_pasar_kg' => 3000,
            'nilai_idr' => 16000,
            'selisih_total' => 8000,
            'komisi_pengepul' => 4000,
            'bagian_admin' => 4000,
            'sudah_disetor' => false,
            'tanggal' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id' => $this->pengepul->id,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.setoran.store'), [
            'transaksi_ids' => [$trx1->id, $trx2->id],
        ]);

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        // Total Nilai Nasabah = 10.000 + 16.000 = Rp 26.000
        // Total Bagian Admin = 2.500 + 4.000 = Rp 6.500
        // Total Disetor = 26.000 + 6.500 = Rp 32.500

        // Verifikasi tabel SetoranPengepul
        $this->assertDatabaseHas('setoran_pengepul', [
            'pengepul_id' => $this->pengepul->id,
            'total_nilai_nasabah' => 26000,
            'total_selisih' => 13000,
            'total_komisi_pengepul' => 6500,
            'total_bagian_admin' => 6500,
            'total_disetor' => 32500,
            'status' => 'menunggu',
        ]);

        // Verifikasi transaksi-transaksi ditandai sebagai sudah disetor
        $trx1->refresh();
        $trx2->refresh();
        $this->assertTrue($trx1->sudah_disetor);
        $this->assertTrue($trx2->sudah_disetor);
    }

    /**
     * Test Admin memverifikasi setoran pengepul.
     */
    public function test_admin_can_verify_setoran()
    {
        $setoran = SetoranPengepul::create([
            'pengepul_id' => $this->pengepul->id,
            'total_nilai_nasabah' => 20000,
            'total_selisih' => 10000,
            'total_komisi_pengepul' => 5000,
            'total_bagian_admin' => 5000,
            'total_disetor' => 25000,
            'transaksi_ids' => [1, 2],
            'status' => 'menunggu',
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.setoran.verify', $setoran->id), [
            'catatan' => 'Sudah ditransfer dan diterima.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi status berubah
        $setoran->refresh();
        $this->assertEquals('terverifikasi', $setoran->status);
        $this->assertEquals($this->admin->id_admin, $setoran->id_admin);
        $this->assertEquals('Sudah ditransfer dan diterima.', $setoran->catatan);
    }

    /**
     * Test Admin menolak setoran pengepul & reset status transaksi terkait.
     */
    public function test_admin_can_reject_setoran_and_individual_transactions_are_reset()
    {
        $trx = TransaksiPengepul::create([
            'pengepul_id' => $this->pengepul->id,
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah' => $this->sampah->id_sampah,
            'berat_kg' => 10,
            'harga_beli_kg' => 2000,
            'harga_pasar_kg' => 3000,
            'nilai_idr' => 20000,
            'selisih_total' => 10000,
            'komisi_pengepul' => 5000,
            'bagian_admin' => 5000,
            'sudah_disetor' => true, // diset true saat setoran dibuat
            'tanggal' => now()->toDateString(),
        ]);

        $setoran = SetoranPengepul::create([
            'pengepul_id' => $this->pengepul->id,
            'total_nilai_nasabah' => 20000,
            'total_selisih' => 10000,
            'total_komisi_pengepul' => 5000,
            'total_bagian_admin' => 5000,
            'total_disetor' => 25000,
            'transaksi_ids' => [$trx->id],
            'status' => 'menunggu',
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.setoran.reject', $setoran->id), [
            'catatan' => 'Uang setoran kurang.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi status setoran ditolak
        $setoran->refresh();
        $this->assertEquals('ditolak', $setoran->status);
        $this->assertEquals($this->admin->id_admin, $setoran->id_admin);
        $this->assertEquals('Uang setoran kurang.', $setoran->catatan);

        // Verifikasi transaksi-transaksi di-reset ke sudah_disetor = false
        $trx->refresh();
        $this->assertFalse($trx->sudah_disetor);
    }

    /**
     * Test Nasabah mengajukan pencairan saldo dengan minimal saldo Rp 100.000.
     */
    public function test_nasabah_withdrawal_request_minimal_balance_validation()
    {
        // Kasus 1: Tarik kurang dari minimal (di bawah Rp 100.000)
        $this->nasabah->nasabah_saldo = 120000;
        $this->nasabah->save();

        $response = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'tarik_jumlah' => 50000, // di bawah Rp 100.000
            'tarik_bank_tujuan' => 'BCA',
            'tarik_nomor_rekening' => '1234567890',
            'tarik_atas_nama' => 'Budi Susanto',
        ]);

        $response->assertSessionHasErrors('tarik_jumlah');
        $this->assertDatabaseMissing('transaksi_tarik', [
            'id_nasabah' => $this->nasabah->id_nasabah,
        ]);

        // Kasus 2: Tarik nominal valid tapi saldo nasabah kurang
        $this->nasabah->nasabah_saldo = 80000; // di bawah Rp 100.000
        $this->nasabah->save();

        $response2 = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'tarik_jumlah' => 100000,
            'tarik_bank_tujuan' => 'BCA',
            'tarik_nomor_rekening' => '1234567890',
            'tarik_atas_nama' => 'Budi Susanto',
        ]);

        $response2->assertSessionHas('error');
        $this->assertDatabaseMissing('transaksi_tarik', [
            'id_nasabah' => $this->nasabah->id_nasabah,
        ]);

        // Kasus 3: Pengajuan valid (di atas min, saldo cukup)
        $this->nasabah->nasabah_saldo = 150000;
        $this->nasabah->save();

        $response3 = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'tarik_jumlah' => 120000,
            'tarik_bank_tujuan' => 'BCA',
            'tarik_nomor_rekening' => '1234567890',
            'tarik_atas_nama' => 'Budi Susanto',
        ]);

        $response3->assertSessionHas('success');
        $this->assertDatabaseHas('transaksi_tarik', [
            'id_nasabah' => $this->nasabah->id_nasabah,
            'tarik_jumlah' => 120000,
            'tarik_bank_tujuan' => 'BCA',
            'tarik_nomor_rekening' => '1234567890',
            'tarik_atas_nama' => 'Budi Susanto',
            'status' => 'menunggu',
        ]);
    }

    /**
     * Test Admin menyetujui pengajuan pencairan saldo nasabah.
     */
    public function test_admin_can_approve_pencairan_and_balance_is_deducted()
    {
        $this->nasabah->nasabah_saldo = 150000;
        $this->nasabah->save();

        $tabungan = Tabungan::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $tabungan->tabungan_saldo_akhir = 150000;
        $tabungan->save();

        $tarik = TransaksiTarik::create([
            'tarik_tanggal' => now()->toDateString(),
            'tarik_jumlah' => 100000,
            'tarik_bank_tujuan' => 'Mandiri',
            'tarik_nomor_rekening' => '0987654321',
            'tarik_atas_nama' => 'Budi Susanto',
            'tarik_sisa_saldo' => 50000,
            'status' => 'menunggu',
            'id_nasabah' => $this->nasabah->id_nasabah,
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.pencairan.approve', $tarik->id_tarik), [
            'catatan' => 'Disetujui dan dibayarkan tunai.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi status pengajuan disetujui
        $tarik->refresh();
        $this->assertEquals('disetujui', $tarik->status);
        $this->assertEquals($this->admin->id_admin, $tarik->id_admin);
        $this->assertEquals('Disetujui dan dibayarkan tunai.', $tarik->catatan);

        // Verifikasi saldo Nasabah dipotong
        $this->nasabah->refresh();
        $this->assertEquals(50000, $this->nasabah->nasabah_saldo);

        // Verifikasi tabungan nasabah dipotong
        $tabungan->refresh();
        $this->assertEquals(50000, $tabungan->tabungan_saldo_akhir);
        $this->assertEquals(100000, $tabungan->tabungan_total_tarik);
    }

    /**
     * Test Admin menolak pengajuan pencairan saldo nasabah.
     */
    public function test_admin_can_reject_pencairan()
    {
        $this->nasabah->nasabah_saldo = 150000;
        $this->nasabah->save();

        $tarik = TransaksiTarik::create([
            'tarik_tanggal' => now()->toDateString(),
            'tarik_jumlah' => 100000,
            'tarik_bank_tujuan' => 'Dana',
            'tarik_nomor_rekening' => '08123456789',
            'tarik_atas_nama' => 'Budi Susanto',
            'tarik_sisa_saldo' => 50000,
            'status' => 'menunggu',
            'id_nasabah' => $this->nasabah->id_nasabah,
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.pencairan.reject', $tarik->id_tarik), [
            'catatan' => 'Ditolak karena tidak sesuai ketentuan.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi status pengajuan ditolak
        $tarik->refresh();
        $this->assertEquals('ditolak', $tarik->status);
        $this->assertEquals('Ditolak karena tidak sesuai ketentuan.', $tarik->catatan);

        // Verifikasi saldo Nasabah TIDAK dipotong
        $this->nasabah->refresh();
        $this->assertEquals(150000, $this->nasabah->nasabah_saldo);
    }

    /**
     * Test Admin can CRUD Hadiah catalog.
     */
    public function test_admin_can_crud_hadiah()
    {
        // 1. Create Hadiah
        $responseStore = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.store'), [
            'nama_hadiah' => 'Tumbler Eco Lestari',
            'poin_butuh' => 250,
            'stok' => 15,
            'keterangan' => 'Tumbler stainless premium'
        ]);

        $responseStore->assertRedirect();
        $responseStore->assertSessionHas('success');

        $this->assertDatabaseHas('hadiah', [
            'nama_hadiah' => 'Tumbler Eco Lestari',
            'poin_butuh' => 250,
            'stok' => 15,
        ]);

        $hadiah = Hadiah::where('nama_hadiah', 'Tumbler Eco Lestari')->first();

        // 2. Update Hadiah
        $responseUpdate = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.update', $hadiah->id_hadiah), [
            'nama_hadiah' => 'Tumbler Eco Lestari V2',
            'poin_butuh' => 200,
            'stok' => 20,
            'keterangan' => 'Tumbler stainless upgraded'
        ]);

        $responseUpdate->assertRedirect();
        $responseUpdate->assertSessionHas('success');

        $this->assertDatabaseHas('hadiah', [
            'id_hadiah' => $hadiah->id_hadiah,
            'nama_hadiah' => 'Tumbler Eco Lestari V2',
            'poin_butuh' => 200,
            'stok' => 20,
        ]);

        // 3. Delete Hadiah
        $responseDelete = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.delete', $hadiah->id_hadiah));

        $responseDelete->assertRedirect();
        $responseDelete->assertSessionHas('success');

        $this->assertDatabaseMissing('hadiah', [
            'id_hadiah' => $hadiah->id_hadiah,
        ]);
    }

    /**
     * Test Nasabah can redeem rewards.
     */
    public function test_nasabah_can_redeem_points_with_sufficient_balance_and_stock()
    {
        $hadiah = Hadiah::create([
            'nama_hadiah' => 'Hadiah Test',
            'poin_butuh' => 100,
            'stok' => 5,
            'keterangan' => 'Deskripsi'
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 300;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi Poin Dipotong
        $gamifikasi->refresh();
        $this->assertEquals(100, $gamifikasi->poin_diperoleh);

        // Verifikasi Stok Dipotong
        $hadiah->refresh();
        $this->assertEquals(3, $hadiah->stok);

        // Verifikasi Database Penukaran
        $this->assertDatabaseHas('penukaran_reward', [
            'id_nasabah' => $this->nasabah->id_nasabah,
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 2,
            'total_poin_ditukar' => 200,
            'status' => 'menunggu',
        ]);
    }

    /**
     * Test Nasabah cannot redeem points if points are insufficient.
     */
    public function test_nasabah_cannot_redeem_points_with_insufficient_points()
    {
        $hadiah = Hadiah::create([
            'nama_hadiah' => 'Hadiah Test 2',
            'poin_butuh' => 100,
            'stok' => 5,
            'keterangan' => 'Deskripsi'
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 50;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Verifikasi Poin TIDAK Dipotong
        $gamifikasi->refresh();
        $this->assertEquals(50, $gamifikasi->poin_diperoleh);

        // Verifikasi Stok TIDAK Dipotong
        $hadiah->refresh();
        $this->assertEquals(5, $hadiah->stok);

        // Verifikasi tidak ada penukaran baru
        $this->assertDatabaseMissing('penukaran_reward', [
            'id_nasabah' => $this->nasabah->id_nasabah,
            'id_hadiah' => $hadiah->id_hadiah,
        ]);
    }

    /**
     * Test Nasabah cannot redeem points if stock is insufficient.
     */
    public function test_nasabah_cannot_redeem_points_with_insufficient_stock()
    {
        $hadiah = Hadiah::create([
            'nama_hadiah' => 'Hadiah Test 3',
            'poin_butuh' => 100,
            'stok' => 1,
            'keterangan' => 'Deskripsi'
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 500;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id' => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Verifikasi Poin TIDAK Dipotong
        $gamifikasi->refresh();
        $this->assertEquals(500, $gamifikasi->poin_diperoleh);

        // Verifikasi Stok TIDAK Dipotong
        $hadiah->refresh();
        $this->assertEquals(1, $hadiah->stok);
    }

    /**
     * Test Admin can approve redemption.
     */
    public function test_admin_can_approve_reward_redemption()
    {
        $hadiah = Hadiah::create([
            'nama_hadiah' => 'Hadiah Test 4',
            'poin_butuh' => 100,
            'stok' => 10,
        ]);

        $penukaran = PenukaranReward::create([
            'id_nasabah' => $this->nasabah->id_nasabah,
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 2,
            'total_poin_ditukar' => 200,
            'status' => 'menunggu',
            'tanggal_tukar' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.penukaran.approve', $penukaran->id_penukaran));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $penukaran->refresh();
        $this->assertEquals('diambil', $penukaran->status);
        $this->assertEquals($this->admin->id_admin, $penukaran->id_admin);
    }

    /**
     * Test Admin can reject redemption and refund points and stock.
     */
    public function test_admin_can_reject_reward_redemption_and_refund_points_and_stock()
    {
        $hadiah = Hadiah::create([
            'nama_hadiah' => 'Hadiah Test 5',
            'poin_butuh' => 100,
            'stok' => 8,
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 100;
        $gamifikasi->save();

        $penukaran = PenukaranReward::create([
            'id_nasabah' => $this->nasabah->id_nasabah,
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah' => 2,
            'total_poin_ditukar' => 200,
            'status' => 'menunggu',
            'tanggal_tukar' => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id' => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.penukaran.reject', $penukaran->id_penukaran), [
            'catatan' => 'Barang rusak/out of stock',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $penukaran->refresh();
        $this->assertEquals('ditolak', $penukaran->status);
        $this->assertEquals('Barang rusak/out of stock', $penukaran->catatan);

        // Verifikasi Poin Dikembalikan (100 + 200 = 300)
        $gamifikasi->refresh();
        $this->assertEquals(300, $gamifikasi->poin_diperoleh);

        // Verifikasi Stok Dikembalikan (8 + 2 = 10)
        $hadiah->refresh();
        $this->assertEquals(10, $hadiah->stok);
    }
}
