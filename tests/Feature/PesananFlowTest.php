<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\User\UserDetailPesanan;
use App\Livewire\Admin\Pesanan as AdminPesanan;
use App\Livewire\Worker\WorkerPesanan;

class PesananFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'owner']);
        Role::firstOrCreate(['name' => 'worker']);
        Role::firstOrCreate(['name' => 'user']);
    }

    public function test_complete_pesanan_flow(): void
    {
        // 1. Create a user
        $user = User::factory()->create(['name' => 'Test User', 'username' => 'testuser']);
        $user->roles()->attach(Role::where('name', 'user')->first());

        // 2. Create a product
        $produk = Produk::create([
            'nama' => 'Batu Bata',
            'jenis' => 'batubata',
            'harga' => 1000,
            'satuan' => 'pcs',
            'deskripsi' => 'Batu bata kualitas super',
        ]);

        // 3. Create an admin
        $admin = User::factory()->create(['username' => 'adminuser']);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // 4. Create a worker
        $worker = User::factory()->create(['username' => 'workeruser']);
        $worker->roles()->attach(Role::where('name', 'worker')->first());

        // 5. User creates order using UserDetailPesanan Livewire component
        $this->actingAs($user);

        Livewire::test(UserDetailPesanan::class)
            ->set('nama_pembeli', 'Test User')
            ->set('selectedProdukId', $produk->id)
            ->set('jumlah', 1500)
            ->set('alamat', 'Jl. Test No. 123')
            ->set('no_whatsapp', '081234567890')
            ->call('kirimPesanan')
            ->assertRedirect(route('user.pesanan'));

        // Assert order exists in database with status pending
        $pesanan = Pesanan::first();
        $this->assertNotNull($pesanan);
        $this->assertEquals('pending', $pesanan->status);
        $this->assertEquals(1500 * 1000, $pesanan->total_harga);

        // 6. Admin accepts order using Admin\Pesanan Livewire component
        $this->actingAs($admin);

        Livewire::test(AdminPesanan::class)
            ->call('acceptPesanan', $pesanan->id);

        $pesanan->refresh();
        $this->assertEquals('dalam_antrian', $pesanan->status);
        $this->assertEquals('belum_dibayar', $pesanan->payment_status);

        // 7. Worker processes order using WorkerPesanan Livewire component
        $this->actingAs($worker);

        Livewire::test(WorkerPesanan::class)
            ->call('proseskan', $pesanan->id);

        $pesanan->refresh();
        $this->assertEquals('diproses', $pesanan->status);

        // 8. Worker confirms delivery
        Livewire::test(WorkerPesanan::class)
            ->call('konfirmasiKirim', $pesanan->id);

        $pesanan->refresh();
        $this->assertEquals('terkirim', $pesanan->status);

        // 9. Worker confirms COD payment
        Livewire::test(WorkerPesanan::class)
            ->call('konfirmasiCOD', $pesanan->id);

        $pesanan->refresh();
        $this->assertEquals('telah_dibayar', $pesanan->payment_status);
        $this->assertNotNull($pesanan->paid_at);
    }
}
