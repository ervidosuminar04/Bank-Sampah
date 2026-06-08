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
use Illuminate\Http\UploadedFile;
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
            'sampah_nama'        => 'Plastik Premium',
            'sampah_jenis'       => 'Anorganik',
            'sampah_satuan'      => 'kg',
            'sampah_harga_kg'    => 2000,
            'sampah_harga_pasar' => 3000,
            'sampah_keterangan'  => 'Plastik kualitas tinggi',
        ]);

        // 2. Buat Pengepul
        $this->pengepul = Pengepul::create([
            'pengepul_nama'         => 'Pengepul Berjaya',
            'pengepul_alamat'       => 'Jl. Pengepul No. 5',
            'pengepul_telepon'      => '081234567890',
            'pengepul_username'     => 'pengepultest_' . uniqid(),
            'pengepul_password'     => bcrypt('password123'),
            'pengepul_komisi_persen'=> 50,
            'pengepul_status_aktif' => 'aktif',
        ]);

        // 3. Buat Nasabah
        $this->nasabah = Nasabah::create([
            'nasabah_nama'     => 'Budi Eco',
            'nasabah_nik'      => '1234567890123456',
            'nasabah_alamat'   => 'Jl. Hijau Lestari',
            'nasabah_telepon'  => '089876543210',
            'nasabah_email'    => 'budi@eco.com',
            'nasabah_username' => 'budieco_' . uniqid(),
            'nasabah_password' => bcrypt('password123'),
            'nasabah_saldo'    => 0,
            'nasabah_tgl_daftar' => now()->toDateString(),
            'nasabah_status'   => 'aktif',
        ]);

        // Buat Tabungan & Gamifikasi Nasabah
        Tabungan::create([
            'id_nasabah'            => $this->nasabah->id_nasabah,
            'tabungan_no_rekening'  => 'BS-TEST-' . rand(1000, 9999),
            'tabungan_total_setor'  => 0,
            'tabungan_total_tarik'  => 0,
            'tabungan_saldo_akhir'  => 0,
            'tabungan_tgl_update'   => now(),
        ]);

        Gamifikasi::create([
            'id_nasabah'     => $this->nasabah->id_nasabah,
            'poin_diperoleh' => 0,
            'total_poin'     => 0,
            'level_nasabah'  => 'Pemula',
            'badge'          => 'Eco Starter',
            'tanggal_update' => now(),
        ]);

        // 4. Buat Admin
        $this->admin = Admin::create([
            'admin_nama'     => 'Super Admin',
            'admin_username' => 'admintest_' . uniqid(),
            'admin_password' => bcrypt('password123'),
            'admin_status'   => 'aktif',
        ]);
    }

    /**
     * Test Pengepul menimbang sampah nasabah & perhitungan finansial 50/50 komisi.
     */
    public function test_pengepul_can_setor_sampah_and_finances_are_calculated_correctly()
    {
        $payload = [
            'nasabah_id' => $this->nasabah->id_nasabah,
            'id_sampah'  => $this->sampah->id_sampah,
            'berat_kg'   => 10,
            'keterangan' => 'Timbangan tes 10kg plastik',
            'foto_dokumentasi' => UploadedFile::fake()->create('foto_timbangan.jpg', 100, 'image/jpeg'),
        ];

        $response = $this->withSession([
            'user_id'   => $this->pengepul->id_pengepul,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.setor'), $payload);

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        // nilai_idr = 2000 * 10 = 20.000
        $this->assertDatabaseHas('transaksi_pengepul', [
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 10,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 20000,
            'selisih_total'                      => 10000,
            'transaksi_pengepul_komisi_pengepul' => 5000,
            'bagian_admin'                       => 5000,
            'sudah_disetor'                      => false,
        ]);

        $this->nasabah->refresh();
        $this->assertEquals(20000, $this->nasabah->nasabah_saldo);

        $tabungan = Tabungan::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $this->assertEquals(20000, $tabungan->tabungan_saldo_akhir);
        $this->assertEquals(20000, $tabungan->tabungan_total_setor);

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
        $trx1 = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 5,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 10000,
            'selisih_total'                      => 5000,
            'transaksi_pengepul_komisi_pengepul' => 2500,
            'bagian_admin'                       => 2500,
            'sudah_disetor'                      => false,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $trx2 = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 8,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 16000,
            'selisih_total'                      => 8000,
            'transaksi_pengepul_komisi_pengepul' => 4000,
            'bagian_admin'                       => 4000,
            'sudah_disetor'                      => false,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id'   => $this->pengepul->id_pengepul,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.setoran.store'), [
            'transaksi_ids'    => [$trx1->id_transaksi_pengepul, $trx2->id_transaksi_pengepul],
            'foto_dokumentasi' => UploadedFile::fake()->create('foto_setoran.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('setoran_pengepul', [
            'pengepul_id'           => $this->pengepul->id_pengepul,
            'total_nilai_nasabah'   => 26000,
            'total_selisih'         => 13000,
            'total_komisi_pengepul' => 6500,
            'total_bagian_admin'    => 6500,
            'total_disetor'         => 32500,
            'setoran_pengepul_status' => 'menunggu',
        ]);

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
            'pengepul_id'             => $this->pengepul->id_pengepul,
            'total_nilai_nasabah'     => 20000,
            'total_selisih'           => 10000,
            'total_komisi_pengepul'   => 5000,
            'total_bagian_admin'      => 5000,
            'total_disetor'           => 25000,
            'transaksi_ids'           => json_encode([1, 2]),
            'setoran_pengepul_status' => 'menunggu',
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.setoran.verify', $setoran->id_setoran_pengepul), [
            'catatan' => 'Sudah ditransfer dan diterima.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setoran->refresh();
        $this->assertEquals('terverifikasi', $setoran->setoran_pengepul_status);
        $this->assertEquals($this->admin->id_admin, $setoran->id_admin);
        $this->assertEquals('Sudah ditransfer dan diterima.', $setoran->catatan);
    }

    /**
     * Test Admin menolak setoran pengepul & reset status transaksi terkait.
     */
    public function test_admin_can_reject_setoran_and_individual_transactions_are_reset()
    {
        $trx = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 10,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 20000,
            'selisih_total'                      => 10000,
            'transaksi_pengepul_komisi_pengepul' => 5000,
            'bagian_admin'                       => 5000,
            'sudah_disetor'                      => true,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $setoran = SetoranPengepul::create([
            'pengepul_id'             => $this->pengepul->id_pengepul,
            'total_nilai_nasabah'     => 20000,
            'total_selisih'           => 10000,
            'total_komisi_pengepul'   => 5000,
            'total_bagian_admin'      => 5000,
            'total_disetor'           => 25000,
            'transaksi_ids'           => json_encode([$trx->id_transaksi_pengepul]),
            'setoran_pengepul_status' => 'menunggu',
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.setoran.reject', $setoran->id_setoran_pengepul), [
            'catatan' => 'Uang setoran kurang.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setoran->refresh();
        $this->assertEquals('ditolak', $setoran->setoran_pengepul_status);
        $this->assertEquals($this->admin->id_admin, $setoran->id_admin);
        $this->assertEquals('Uang setoran kurang.', $setoran->catatan);

        $trx->refresh();
        $this->assertFalse($trx->sudah_disetor);
    }

    /**
     * Test Nasabah mengajukan pencairan saldo dengan minimal saldo Rp 100.000.
     */
    public function test_nasabah_withdrawal_request_minimal_balance_validation()
    {
        // Kasus 1: Tarik kurang dari minimal
        $this->nasabah->nasabah_saldo = 120000;
        $this->nasabah->save();

        $response = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'transaksi_tarik_jumlah'         => 50000,
            'transaksi_tarik_bank_tujuan'    => 'BCA',
            'transaksi_tarik_nomor_rekening' => '1234567890',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
        ]);

        $response->assertSessionHasErrors('transaksi_tarik_jumlah');
        $this->assertDatabaseMissing('transaksi_tarik', ['id_nasabah' => $this->nasabah->id_nasabah]);

        // Kasus 2: Saldo kurang
        $this->nasabah->nasabah_saldo = 80000;
        $this->nasabah->save();

        $response2 = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'transaksi_tarik_jumlah'         => 100000,
            'transaksi_tarik_bank_tujuan'    => 'BCA',
            'transaksi_tarik_nomor_rekening' => '1234567890',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
        ]);

        $response2->assertSessionHas('error');
        $this->assertDatabaseMissing('transaksi_tarik', ['id_nasabah' => $this->nasabah->id_nasabah]);

        // Kasus 3: Valid
        $this->nasabah->nasabah_saldo = 150000;
        $this->nasabah->save();

        $response3 = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.pencairan.store'), [
            'transaksi_tarik_jumlah'         => 120000,
            'transaksi_tarik_bank_tujuan'    => 'BCA',
            'transaksi_tarik_nomor_rekening' => '1234567890',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
        ]);

        $response3->assertSessionHas('success');
        $this->assertDatabaseHas('transaksi_tarik', [
            'id_nasabah'                     => $this->nasabah->id_nasabah,
            'transaksi_tarik_jumlah'         => 120000,
            'transaksi_tarik_bank_tujuan'    => 'BCA',
            'transaksi_tarik_nomor_rekening' => '1234567890',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
            'transaksi_tarik_status'         => 'menunggu',
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
            'transaksi_tarik_tanggal'        => now()->toDateString(),
            'transaksi_tarik_jumlah'         => 100000,
            'transaksi_tarik_bank_tujuan'    => 'Mandiri',
            'transaksi_tarik_nomor_rekening' => '0987654321',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
            'transaksi_tarik_sisa_saldo'     => 50000,
            'transaksi_tarik_status'         => 'menunggu',
            'id_nasabah'                     => $this->nasabah->id_nasabah,
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.pencairan.approve', $tarik->id_tarik), [
            'catatan'          => 'Disetujui dan dibayarkan tunai.',
            'bukti_pembayaran' => UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $tarik->refresh();
        $this->assertEquals('disetujui', $tarik->transaksi_tarik_status);
        $this->assertEquals($this->admin->id_admin, $tarik->id_admin);
        $this->assertEquals('Disetujui dan dibayarkan tunai.', $tarik->transaksi_tarik_catatan);

        $this->nasabah->refresh();
        $this->assertEquals(50000, $this->nasabah->nasabah_saldo);

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
            'transaksi_tarik_tanggal'        => now()->toDateString(),
            'transaksi_tarik_jumlah'         => 100000,
            'transaksi_tarik_bank_tujuan'    => 'Dana',
            'transaksi_tarik_nomor_rekening' => '08123456789',
            'transaksi_tarik_atas_nama'      => 'Budi Susanto',
            'transaksi_tarik_sisa_saldo'     => 50000,
            'transaksi_tarik_status'         => 'menunggu',
            'id_nasabah'                     => $this->nasabah->id_nasabah,
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.pencairan.reject', $tarik->id_tarik), [
            'catatan' => 'Ditolak karena tidak sesuai ketentuan.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $tarik->refresh();
        $this->assertEquals('ditolak', $tarik->transaksi_tarik_status);
        $this->assertEquals('Ditolak karena tidak sesuai ketentuan.', $tarik->transaksi_tarik_catatan);

        $this->nasabah->refresh();
        $this->assertEquals(150000, $this->nasabah->nasabah_saldo);
    }

    /**
     * Test Admin can CRUD Hadiah catalog.
     */
    public function test_admin_can_crud_hadiah()
    {
        $responseStore = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.store'), [
            'hadiah_nama'       => 'Tumbler Eco Lestari',
            'hadiah_poin_butuh' => 250,
            'hadiah_stok'       => 15,
            'hadiah_keterangan' => 'Tumbler stainless premium',
        ]);

        $responseStore->assertRedirect();
        $responseStore->assertSessionHas('success');

        $this->assertDatabaseHas('hadiah', [
            'hadiah_nama'       => 'Tumbler Eco Lestari',
            'hadiah_poin_butuh' => 250,
            'hadiah_stok'       => 15,
        ]);

        $hadiah = Hadiah::where('hadiah_nama', 'Tumbler Eco Lestari')->first();

        $responseUpdate = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.update', $hadiah->id_hadiah), [
            'hadiah_nama'       => 'Tumbler Eco Lestari V2',
            'hadiah_poin_butuh' => 200,
            'hadiah_stok'       => 20,
            'hadiah_keterangan' => 'Tumbler stainless upgraded',
        ]);

        $responseUpdate->assertRedirect();
        $responseUpdate->assertSessionHas('success');

        $this->assertDatabaseHas('hadiah', [
            'id_hadiah'         => $hadiah->id_hadiah,
            'hadiah_nama'       => 'Tumbler Eco Lestari V2',
            'hadiah_poin_butuh' => 200,
            'hadiah_stok'       => 20,
        ]);

        $responseDelete = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.hadiah.delete', $hadiah->id_hadiah));

        $responseDelete->assertRedirect();
        $responseDelete->assertSessionHas('success');

        $this->assertDatabaseMissing('hadiah', ['id_hadiah' => $hadiah->id_hadiah]);
    }

    /**
     * Test Nasabah can redeem rewards.
     */
    public function test_nasabah_can_redeem_points_with_sufficient_balance_and_stock()
    {
        $hadiah = Hadiah::create([
            'hadiah_nama'       => 'Hadiah Test',
            'hadiah_poin_butuh' => 100,
            'hadiah_stok'       => 5,
            'hadiah_keterangan' => 'Deskripsi',
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 300;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah'    => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $gamifikasi->refresh();
        $this->assertEquals(100, $gamifikasi->poin_diperoleh);

        $hadiah->refresh();
        $this->assertEquals(3, $hadiah->hadiah_stok);

        $this->assertDatabaseHas('penukaran_reward', [
            'id_nasabah'         => $this->nasabah->id_nasabah,
            'id_hadiah'          => $hadiah->id_hadiah,
            'jumlah'             => 2,
            'total_poin_ditukar' => 200,
            'penukaran_status'   => 'menunggu',
        ]);
    }

    /**
     * Test Nasabah cannot redeem with insufficient points.
     */
    public function test_nasabah_cannot_redeem_points_with_insufficient_points()
    {
        $hadiah = Hadiah::create([
            'hadiah_nama'       => 'Hadiah Test 2',
            'hadiah_poin_butuh' => 100,
            'hadiah_stok'       => 5,
            'hadiah_keterangan' => 'Deskripsi',
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 50;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah'    => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $gamifikasi->refresh();
        $this->assertEquals(50, $gamifikasi->poin_diperoleh);

        $hadiah->refresh();
        $this->assertEquals(5, $hadiah->hadiah_stok);
    }

    /**
     * Test Nasabah cannot redeem with insufficient stock.
     */
    public function test_nasabah_cannot_redeem_points_with_insufficient_stock()
    {
        $hadiah = Hadiah::create([
            'hadiah_nama'       => 'Hadiah Test 3',
            'hadiah_poin_butuh' => 100,
            'hadiah_stok'       => 1,
            'hadiah_keterangan' => 'Deskripsi',
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 500;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id'   => $this->nasabah->id_nasabah,
            'user_type' => 'nasabah',
        ])->post(route('nasabah.tukar_poin'), [
            'id_hadiah' => $hadiah->id_hadiah,
            'jumlah'    => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $hadiah->refresh();
        $this->assertEquals(1, $hadiah->hadiah_stok);
    }

    /**
     * Test Admin can approve redemption.
     */
    public function test_admin_can_approve_reward_redemption()
    {
        $hadiah = Hadiah::create([
            'hadiah_nama'       => 'Hadiah Test 4',
            'hadiah_poin_butuh' => 100,
            'hadiah_stok'       => 10,
        ]);

        $penukaran = PenukaranReward::create([
            'id_nasabah'         => $this->nasabah->id_nasabah,
            'id_hadiah'          => $hadiah->id_hadiah,
            'jumlah'             => 2,
            'total_poin_ditukar' => 200,
            'penukaran_status'   => 'menunggu',
            'tanggal_tukar'      => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.penukaran.approve', $penukaran->id_penukaran));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $penukaran->refresh();
        $this->assertEquals('diambil', $penukaran->penukaran_status);
        $this->assertEquals($this->admin->id_admin, $penukaran->id_admin);
    }

    /**
     * Test Admin can reject redemption and refund points and stock.
     */
    public function test_admin_can_reject_reward_redemption_and_refund_points_and_stock()
    {
        $hadiah = Hadiah::create([
            'hadiah_nama'       => 'Hadiah Test 5',
            'hadiah_poin_butuh' => 100,
            'hadiah_stok'       => 8,
        ]);

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 100;
        $gamifikasi->save();

        $penukaran = PenukaranReward::create([
            'id_nasabah'         => $this->nasabah->id_nasabah,
            'id_hadiah'          => $hadiah->id_hadiah,
            'jumlah'             => 2,
            'total_poin_ditukar' => 200,
            'penukaran_status'   => 'menunggu',
            'tanggal_tukar'      => now()->toDateString(),
        ]);

        $response = $this->withSession([
            'user_id'   => $this->admin->id_admin,
            'user_type' => 'admin',
        ])->post(route('admin.penukaran.reject', $penukaran->id_penukaran), [
            'catatan' => 'Barang rusak/out of stock',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $penukaran->refresh();
        $this->assertEquals('ditolak', $penukaran->penukaran_status);
        $this->assertEquals('Barang rusak/out of stock', $penukaran->catatan);

        $gamifikasi->refresh();
        $this->assertEquals(300, $gamifikasi->poin_diperoleh);

        $hadiah->refresh();
        $this->assertEquals(10, $hadiah->hadiah_stok);
    }

    /**
     * Test pilih pengepul tanpa password (passwordless selection).
     */
    public function test_pengepul_dapat_dipilih_tanpa_password()
    {
        $response = $this->get(route('pengepul.pilih'));
        $response->assertOk();
        $response->assertSee($this->pengepul->pengepul_nama);

        $response = $this->get(route('pengepul.select', $this->pengepul->id_pengepul));
        $response->assertRedirect(route('pengepul.dashboard'));

        $response->assertSessionHas('user_id', $this->pengepul->id_pengepul);
        $response->assertSessionHas('user_type', 'pengepul');
    }

    /**
     * Test Pengepul edit transaksi & finansial nasabah disesuaikan.
     */
    public function test_pengepul_can_edit_transaksi_and_nasabah_finances_are_recalculated()
    {
        $transaksi = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 10,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 20000,
            'selisih_total'                      => 10000,
            'transaksi_pengepul_komisi_pengepul' => 5000,
            'bagian_admin'                       => 5000,
            'sudah_disetor'                      => false,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $this->nasabah->nasabah_saldo = 20000;
        $this->nasabah->save();

        $tabungan = Tabungan::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $tabungan->tabungan_total_setor = 20000;
        $tabungan->tabungan_saldo_akhir = 20000;
        $tabungan->save();

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 100;
        $gamifikasi->total_poin = 100;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id'   => $this->pengepul->id_pengepul,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.transaksi.update', $transaksi->id_transaksi_pengepul), [
            'id_sampah'  => $this->sampah->id_sampah,
            'berat_kg'   => 5,
            'keterangan' => 'Dikoreksi 5kg',
        ]);

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        $transaksi->refresh();
        $this->assertEquals(5, $transaksi->berat_kg);
        $this->assertEquals(10000, $transaksi->nilai_idr);

        $this->nasabah->refresh();
        $this->assertEquals(10000, $this->nasabah->nasabah_saldo);

        $tabungan->refresh();
        $this->assertEquals(10000, $tabungan->tabungan_saldo_akhir);

        $gamifikasi->refresh();
        $this->assertEquals(50, $gamifikasi->total_poin);
    }

    /**
     * Test Pengepul hapus transaksi & saldo nasabah dikembalikan.
     */
    public function test_pengepul_can_delete_transaksi_and_nasabah_balance_is_reversed()
    {
        $transaksi = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 8,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 16000,
            'selisih_total'                      => 8000,
            'transaksi_pengepul_komisi_pengepul' => 4000,
            'bagian_admin'                       => 4000,
            'sudah_disetor'                      => false,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $this->nasabah->nasabah_saldo = 16000;
        $this->nasabah->save();

        $tabungan = Tabungan::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $tabungan->tabungan_total_setor = 16000;
        $tabungan->tabungan_saldo_akhir = 16000;
        $tabungan->save();

        $gamifikasi = Gamifikasi::where('id_nasabah', $this->nasabah->id_nasabah)->first();
        $gamifikasi->poin_diperoleh = 80;
        $gamifikasi->total_poin = 80;
        $gamifikasi->save();

        $response = $this->withSession([
            'user_id'   => $this->pengepul->id_pengepul,
            'user_type' => 'pengepul',
        ])->post(route('pengepul.transaksi.delete', $transaksi->id_transaksi_pengepul));

        $response->assertRedirect(route('pengepul.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('transaksi_pengepul', ['id_transaksi_pengepul' => $transaksi->id_transaksi_pengepul]);

        $this->nasabah->refresh();
        $this->assertEquals(0, $this->nasabah->nasabah_saldo);

        $gamifikasi->refresh();
        $this->assertEquals(0, $gamifikasi->total_poin);
    }

    /**
     * Test Pengepul tidak bisa edit/hapus transaksi yang sudah disetor.
     */
    public function test_pengepul_cannot_edit_or_delete_transaksi_yang_sudah_disetor()
    {
        $transaksi = TransaksiPengepul::create([
            'pengepul_id'                        => $this->pengepul->id_pengepul,
            'nasabah_id'                         => $this->nasabah->id_nasabah,
            'id_sampah'                          => $this->sampah->id_sampah,
            'berat_kg'                           => 5,
            'harga_beli_kg'                      => 2000,
            'harga_pasar_kg'                     => 3000,
            'nilai_idr'                          => 10000,
            'selisih_total'                      => 5000,
            'transaksi_pengepul_komisi_pengepul' => 2500,
            'bagian_admin'                       => 2500,
            'sudah_disetor'                      => true,
            'transaksi_pengepul_tanggal'         => now()->toDateString(),
        ]);

        $session = ['user_id' => $this->pengepul->id_pengepul, 'user_type' => 'pengepul'];

        $responseUpdate = $this->withSession($session)->post(
            route('pengepul.transaksi.update', $transaksi->id_transaksi_pengepul),
            ['id_sampah' => $this->sampah->id_sampah, 'berat_kg' => 1]
        );
        $responseUpdate->assertRedirect(route('pengepul.dashboard'));
        $responseUpdate->assertSessionHas('error');

        $transaksi->refresh();
        $this->assertEquals(5, $transaksi->berat_kg);

        $responseDelete = $this->withSession($session)->post(
            route('pengepul.transaksi.delete', $transaksi->id_transaksi_pengepul)
        );
        $responseDelete->assertRedirect(route('pengepul.dashboard'));
        $responseDelete->assertSessionHas('error');

        $this->assertDatabaseHas('transaksi_pengepul', ['id_transaksi_pengepul' => $transaksi->id_transaksi_pengepul]);
    }
}
