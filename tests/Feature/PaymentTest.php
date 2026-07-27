<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Pemasukan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\MidtransService;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_get_snap_token_generates_token_and_returns_json(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());

        $produk = Produk::create([
            'nama' => 'Batu Bata',
            'jenis' => 'batubata',
            'harga' => 1000,
            'satuan' => 'pcs',
            'deskripsi' => 'Batu bata',
        ]);

        $pesanan = Pesanan::create([
            'nomor' => 'USR-12345',
            'nama' => 'Test User',
            'tipe' => 'batubata',
            'jumlah' => 100,
            'alamat_penjemputan' => '-',
            'alamat_pengiriman' => 'Jl. Test No. 123',
            'status' => 'dalam_antrian',
            'description' => 'Produk: Batu Bata',
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'harga' => 1000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'total_harga' => 100000,
            'no_whatsapp' => '081234567890',
        ]);

        // Mock MidtransService
        $this->mock(MidtransService::class, function ($mock) use ($pesanan) {
            $mock->shouldReceive('generateSnapToken')
                ->once()
                ->with(\Mockery::on(function ($argument) use ($pesanan) {
                    return $argument->id === $pesanan->id;
                }))
                ->andReturn('mocked-snap-token-123');
        });

        $response = $this->actingAs($user)
            ->postJson(route('pesanan.snap-token', ['id' => $pesanan->id]));

        $response->assertOk()
            ->assertJson(['snap_token' => 'mocked-snap-token-123']);

        $pesanan->refresh();
        $this->assertEquals('mocked-snap-token-123', $pesanan->snap_token);
    }

    public function test_midtrans_webhook_handles_settlement_notification(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());

        $pesanan = Pesanan::create([
            'nomor' => 'USR-54321',
            'nama' => 'Test User Webhook',
            'tipe' => 'batubata',
            'jumlah' => 100,
            'alamat_penjemputan' => '-',
            'alamat_pengiriman' => 'Jl. Test No. 123',
            'status' => 'dalam_antrian',
            'description' => 'Produk: Batu Bata',
            'user_id' => $user->id,
            'harga' => 1000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'total_harga' => 100000,
            'no_whatsapp' => '081234567890',
            'payment_status' => 'belum_dibayar',
        ]);

        // Create initial pending Pemasukan (as done during admin confirmation)
        Pemasukan::create([
            'pesanan_id' => $pesanan->id,
            'tanggal' => today(),
            'jumlah' => 100000,
            'keterangan' => "Penjualan: USR-54321",
            'kategori' => 'penjualan',
            'status' => 'pending',
            'user_id' => $user->id,
        ]);

        // Mock parseNotification in MidtransService
        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('parseNotification')
                ->once()
                ->andReturn([
                    'is_valid_signature' => true,
                    'transaction_status' => 'settlement',
                    'order_id' => 'USR-54321-1627889102',
                    'fraud_status' => 'accept',
                    'transaction_id' => 'midtrans-trans-id-999',
                ]);
        });

        // Send POST request (simulate Webhook)
        $response = $this->postJson(route('midtrans.notification'));

        $response->assertOk()
            ->assertJson(['message' => 'OK']);

        $pesanan->refresh();
        $this->assertEquals('telah_dibayar', $pesanan->payment_status);
        $this->assertEquals('midtrans-trans-id-999', $pesanan->midtrans_transaction_id);
        $this->assertNotNull($pesanan->paid_at);

        // Verify Pemasukan is confirmed
        $pemasukan = Pemasukan::where('pesanan_id', $pesanan->id)->first();
        $this->assertNotNull($pemasukan);
        $this->assertEquals('confirmed', $pemasukan->status);
    }

    public function test_midtrans_webhook_rejects_invalid_signature(): void
    {
        $this->mock(MidtransService::class, function ($mock) {
            $mock->shouldReceive('parseNotification')
                ->once()
                ->andReturn([
                    'is_valid_signature' => false,
                    'transaction_status' => 'settlement',
                    'order_id' => 'USR-FAKE-123',
                    'fraud_status' => 'accept',
                    'transaction_id' => 'fake-trans-id',
                ]);
        });

        $response = $this->postJson(route('midtrans.notification'));

        $response->assertStatus(403)
            ->assertJson(['message' => 'Invalid signature key']);
    }
}
