<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Pesanan as PesananModel;
use App\Models\Pemasukan;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

use Livewire\WithPagination;

// #[Layout] mengatur template yang membungkus komponen ini (Master Admin Layout)
#[Layout('layouts.app')]
class Pesanan extends Component
{
    use OwnerAccess, WithPagination;

    // Method mount() berfungsi sebagai constructor/middleware. Melakukan verifikasi apakah user benar-benar admin.
    public function mount(string $owner = ''): void
    {
        $this->readonly = false;
    }

    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    public $showForm = false;
    public $editingId = null;

    public $showRejectModal = false;
    public $rejectingId = null;

    // Modal Pembayaran Cicilan
    public $showPaymentModal  = false;
    public $paymentPesananId  = null;
    public $paymentJumlah     = '';
    public $paymentTipe       = ''; // 'admin' | 'cod'
    public $paymentTotalHarga = 0;
    public $paymentKekurangan = 0;
    public $paymentNomor      = '';

    public $selectedPesananId = null;

    public $nama = '';
    public $jumlah = '';
    public $jumlah_halus = '';
    public $jumlah_kasar = '';
    public $harga_halus = '';
    public $harga_kasar = '';
    public $alamat_pengiriman = '';
    public $durasi = '';
    public $catatan = '';
    public $produk_id = null;
    public $no_whatsapp = '';

    // rules() mendefinisikan aturan validasi input pada backend sebelum pesanan dapat ditambahkan atau diperbarui (Server-side Validation)
    public function rules()
    {
        $isGrajen = $this->isGrajen;
        return [
            'nama'               => 'required|string|min:3',
            'produk_id'          => 'required|exists:produks,id',
            'jumlah'             => $isGrajen ? 'nullable|integer|min:0' : 'required|integer|min:1',
            'jumlah_halus'       => $isGrajen ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'jumlah_kasar'       => $isGrajen ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'harga_halus'        => $isGrajen ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'harga_kasar'        => $isGrajen ? 'required|integer|min:0' : 'nullable|integer|min:0',
            'alamat_pengiriman'  => 'required|string|min:5',
            'durasi'             => 'nullable|integer|min:1',
            'catatan'            => 'nullable|string',
            'no_whatsapp'        => 'required|string|min:10',
        ];
    }

    // #[Computed] men-cache hasil query daftar produk di memori server agar tidak bolak-balik memanggil database setiap komponen dirender ulang (optimasi resource)
    #[Computed(cache: true)]
    public function produks()
    {
        return \App\Models\Produk::select('id', 'nama', 'jenis', 'harga')->get();
    }

    // Deteksi apakah produk yang dipilih adalah Grajen (berdasarkan nama produk)
    #[Computed]
    public function isGrajen(): bool
    {
        if (!$this->produk_id) return false;
        $produk = $this->produks->firstWhere('id', $this->produk_id);
        return $produk && str_contains(strtolower($produk->nama), 'grajen');
    }

    // #[Computed] mengambil semua daftar pesanan beserta data relasinya (Eager Loading: with user & produk)
    // Diurutkan berdasarkan status 'pending' agar pesanan baru selalu berada di urutan paling atas (prioritas UI)
    #[Computed]
    public function pesanans()
    {
        return PesananModel::with(['user', 'produk'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function pesananStats()
    {
        return PesananModel::selectRaw("
            count(*) as total,
            sum(case when status = 'pending' then 1 else 0 end) as pending,
            sum(case when status = 'dalam_antrian' then 1 else 0 end) as dalam_antrian,
            sum(case when status = 'diproses' then 1 else 0 end) as diproses,
            sum(case when status = 'terkirim' then 1 else 0 end) as terkirim
        ")->first();
    }

    #[Computed]
    public function selectedPesanan()
    {
        if (!$this->selectedPesananId) return null;
        return PesananModel::with(['user', 'produk'])->find($this->selectedPesananId);
    }

    public function showDetail($id): void
    {
        $this->selectedPesananId = $id;
    }

    public function closeDetail(): void
    {
        $this->selectedPesananId = null;
    }

    private function invalidatePesananCache()
    {
        $this->dispatch('DISPATCH_INVALIDATE_PESANAN');
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetAttributes();
        $this->editingId = null;
        $this->resetValidation();
    }

    private function resetAttributes()
    {
        $this->nama = '';
        $this->jumlah = '';
        $this->jumlah_halus = '';
        $this->jumlah_kasar = '';
        $this->harga_halus = '';
        $this->harga_kasar = '';
        $this->alamat_pengiriman = '';
        $this->durasi = '';
        $this->catatan = '';
        $this->produk_id = null;
        $this->no_whatsapp = '';
    }

    public function editPesanan($id)
    {
        $pesanan = PesananModel::select([
            'id', 'nama', 'jumlah',
            'alamat_pengiriman',
            'produk_id', 'no_whatsapp', 'durasi', 'catatan',
        ])->findOrFail($id);

        $this->editingId          = $id;
        $this->nama               = $pesanan->nama;
        $this->jumlah             = $pesanan->jumlah;
        $this->alamat_pengiriman  = $pesanan->alamat_pengiriman;
        $this->durasi             = $pesanan->durasi;
        $this->catatan            = $pesanan->catatan;
        $this->produk_id          = $pesanan->produk_id;
        $this->no_whatsapp        = $pesanan->no_whatsapp;
        $this->showForm = true;
    }

    public function tambahPesanan()
    {
        $this->validate();

        // Ambil data produk untuk mengisi field-field otomatis (sesuai alur panel User)
        $produk = \App\Models\Produk::findOrFail($this->produk_id);

        // Kalkulasi jumlah & total: jika Grajen, gabungkan halus + kasar
        if ($this->isGrajen) {
            $jumlahTotal   = (int)($this->jumlah_halus ?? 0) + (int)($this->jumlah_kasar ?? 0);
            $totalHarga    = ((int)($this->jumlah_halus ?? 0) * (int)($this->harga_halus ?? 0))
                           + ((int)($this->jumlah_kasar ?? 0) * (int)($this->harga_kasar ?? 0));
            $deskripsiProduk = "Produk: {$produk->nama} ({$produk->jenis})";

            // Rincian perhitungan disimpan sebagai catatan/note
            $catatanGrajen = "Halus: {$this->jumlah_halus} {$produk->satuan} × Rp" . number_format((int)$this->harga_halus, 0, ',', '.')
                           . " = Rp" . number_format((int)($this->jumlah_halus ?? 0) * (int)($this->harga_halus ?? 0), 0, ',', '.')
                           . "\nKasar: {$this->jumlah_kasar} {$produk->satuan} × Rp" . number_format((int)$this->harga_kasar, 0, ',', '.')
                           . " = Rp" . number_format((int)($this->jumlah_kasar ?? 0) * (int)($this->harga_kasar ?? 0), 0, ',', '.')
                           . "\nTotal: Rp" . number_format($totalHarga, 0, ',', '.');
            // Gabungkan dengan catatan manual admin (jika ada)
            $catatanFinal = $catatanGrajen . ($this->catatan ? "\n---\n" . $this->catatan : '');
        } else {
            $jumlahTotal     = (int)$this->jumlah;
            $totalHarga      = in_array($produk->jenis, ['carter', 'sewa'])
                ? ($this->durasi ?? 1) * config('pricing.sewa_per_day', 300000)
                : $jumlahTotal * $produk->harga;
            $deskripsiProduk = "Produk: {$produk->nama} ({$produk->jenis})";
            $catatanFinal    = $this->catatan;
        }


        // Data inti yang identik dengan yang disimpan panel User
        $data = [
            'nama'               => $this->nama,
            'tipe'               => $produk->jenis,       // Otomatis dari produk (sama seperti User)
            'jumlah'             => $jumlahTotal,
            'alamat_penjemputan' => '-',              // Default (admin tidak input GPS)
            'alamat_pengiriman'  => $this->alamat_pengiriman,
            'description'        => $deskripsiProduk, // Otomatis (termasuk breakdown halus/kasar)
            'produk_id'          => $this->produk_id,
            'harga'              => $produk->harga,       // Disimpan seperti panel User
            'ongkos_kirim'       => 0,                    // Default 0 (sama seperti User)
            'jarak'              => 0,                    // Default 0 (sama seperti User)
            'total_harga'        => $totalHarga,
            'catatan'            => $catatanFinal,
            'no_whatsapp'        => $this->no_whatsapp,
            'durasi'             => $this->durasi ?: null,
        ];

        if ($this->editingId) {
            // Mode Edit: perbarui data tanpa mengubah nomor & status
            $pesanan = PesananModel::findOrFail($this->editingId);
            $pesanan->update($data);
            $msg    = 'Pesanan berhasil diperbarui!';
            $action = 'update';
        } else {
            // Mode Baru: generate nomor otomatis & set status awal pending (identik dengan User)
            $data['nomor']   = 'ADM-' . strtoupper(uniqid());
            $data['status']  = 'pending';
            $data['user_id'] = $this->getUserId();
            $pesanan = PesananModel::create($data);
            $msg    = 'Pesanan berhasil ditambahkan!';
            $action = 'create';
        }

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => $action,
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => ($action === 'update' ? 'Update: ' : 'Tambah (Admin): ') . $pesanan->nomor,
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', $msg);
        $this->closeForm();
    }

    /**
     * Logika Inti Konfirmasi Pesanan oleh Admin:
     * - Merubah status pesanan dari 'pending' menjadi 'dalam_antrian' dan payment_status menjadi 'belum_dibayar'
     * - Secara otomatis membuat record/catatan di tabel Pemasukan (Keuangan) dengan status 'pending'
     * - Setelah ini dieksekusi, pesanan akan diteruskan dan muncul di panel Pekerja (Worker)
     */
    public function acceptPesanan($id)
    {
        $pesanan = PesananModel::with('produk')->findOrFail($id);
        if ($pesanan->status !== 'pending') return;

        // Calculate total
        $totalHarga = $this->hitungTotalHarga($pesanan);

        $pesanan->update([
            'status'         => 'dalam_antrian',
            'payment_status' => 'belum_dibayar',
            'total_harga'    => $totalHarga,
        ]);

        // PRE-GENERATE MIDTRANS SNAP TOKEN (Optimization)
        // Dijalankan di background saat admin menerima pesanan agar user tidak menunggu loading lama saat klik Bayar
        $midtransService = app(\App\Services\MidtransService::class);
        $snapToken = $midtransService->generateSnapToken($pesanan);
        if ($snapToken) {
            $pesanan->update(['snap_token' => $snapToken]);
        }

        // Create Pemasukan as pending (will show in "Pemasukan Pending")
        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $totalHarga,
                'keterangan' => "Penjualan: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'pending',
                'user_id'    => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'accept',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Konfirmasi pesanan: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan dikonfirmasi & masuk antrian!');
    }

    public function openRejectModal($id)
    {
        $this->rejectingId = $id;
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectingId = null;
    }

    public function confirmReject($reasonKey)
    {
        if (!$this->rejectingId) return;

        $pesanan = PesananModel::findOrFail($this->rejectingId);
        if ($pesanan->status === 'rejected') return;

        $reasons = [
            'jarak' => 'Mohon maaf pesanan anda telah ditolak, karena alamat pengiriman anda berada diluar jangkauan pengiriman kami.',
            'sedikit' => "Mohon maaf pesanan anda telah ditolak, karena jumlah barang yang anda pesan dibawah batas minimal pesanan dari produk yang anda pilih,\na. Grajen dan kayu sak = 50 - 120 sak jika pesanan lebih dari 120 sak silahkan lakukan pemesanan lagi dengan item yang sama\nb. Kayu Bak = 1 - 5 bak jika pesanan lebih dari 5 bak silahkan lakukan pemesanan lagi dengan item yang sama\nc. Batu bata dan genteng = 1000 - 4000 pcs jika pesanan lebih dari 4000 pcs silahkan lakukan 2x pemesanan",
            'banyak' => "Mohon maaf pesanan anda telah ditolak, karena jumlah barang yang anda pesan diatas batas maksimal pesanan dari produk yang anda pilih,\na. Grajen dan kayu sak = 50 - 120 sak jika pesanan lebih dari 120 sak silahkan lakukan pemesanan lagi dengan item yang sama\nb. Kayu Bak = 1 - 5 bak jika pesanan lebih dari 5 bak silahkan lakukan pemesanan lagi dengan item yang sama\nc. Batu bata dan genteng = 1000 - 4000 pcs jika pesanan lebih dari 4000 pcs silahkan lakukan 2x pemesanan",
        ];

        if (!array_key_exists($reasonKey, $reasons)) return;

        $pesanan->update([
            'status' => 'rejected',
            'alasan_penolakan' => $reasons[$reasonKey]
        ]);

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'reject',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Tolak: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        
        $this->closeRejectModal();
        session()->flash('success', 'Pesanan berhasil ditolak dengan alasan yang dipilih.');
    }

    /**
     * Konfirmasi Pembayaran Manual (Skenario Offline / Bayar di kantor):
     * Mengubah status payment menjadi 'telah_dibayar' dan mencatatnya ke dalam tabel Pemasukan sebagai 'confirmed' (Uang Kas Masuk Real)
     */
    public function konfirmasiPembayaranAdmin($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->payment_status === 'telah_dibayar') return;

        $pesanan->update([
            'payment_status' => 'telah_dibayar',
            'paid_at'        => now(),
        ]);

        Pemasukan::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'tanggal'    => today(),
                'jumlah'     => $pesanan->total_harga,
                'keterangan' => "Pembayaran Kantor: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => $this->getUserId(),
            ]
        );

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => "Konfirmasi bayar kantor: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pembayaran dikonfirmasi!');
    }

    // =========================================================================
    // SISTEM CICILAN (Partial Payment)
    // Saat konfirmasi bayar atau COD diklik, muncul popup input nilai pembayaran.
    // Jika kurang dari total → dicatat sebagai cicilan (catatan + Pemasukan parsial).
    // Jika >= total → langsung lunas.
    // =========================================================================

    public function openPaymentModal($id, $tipe = 'admin'): void
    {
        $pesanan = PesananModel::findOrFail($id);
        if ($pesanan->payment_status === 'telah_dibayar') return;

        $totalTerbayar = (int) \App\Models\Pemasukan::where('pesanan_id', $id)->where('status', 'confirmed')->sum('jumlah');
        $this->paymentKekurangan = max(0, (int) $pesanan->total_harga - $totalTerbayar);

        if ($this->paymentKekurangan <= 0) {
            $pesanan->update([
                'payment_status' => 'telah_dibayar',
                'paid_at'        => now(),
            ]);
            $this->invalidatePesananCache();
            session()->flash('success', 'Status pesanan otomatis diperbarui menjadi lunas karena total pembayaran sudah memenuhi!');
            return;
        }

        $this->paymentPesananId  = $id;
        $this->paymentTipe       = $tipe;
        $this->paymentJumlah     = '';
        $this->paymentTotalHarga = $pesanan->total_harga;
        $this->paymentNomor      = $pesanan->nomor;
        $this->showPaymentModal  = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal  = false;
        $this->paymentPesananId  = null;
        $this->paymentJumlah     = '';
        $this->paymentTipe       = '';
        $this->paymentTotalHarga = 0;
        $this->paymentKekurangan = 0;
        $this->paymentNomor      = '';
    }

    public function simpanPembayaran(): void
    {
        $this->validate([
            'paymentJumlah' => 'required|numeric|min:1|max:' . $this->paymentKekurangan
        ], [
            'paymentJumlah.max' => 'Nominal pembayaran tidak boleh melebihi total kekurangan (Rp' . number_format($this->paymentKekurangan, 0, ',', '.') . ').'
        ]);

        $pesanan     = PesananModel::findOrFail($this->paymentPesananId);
        $jumlahBayar = (int) $this->paymentJumlah;
        $totalHarga  = (int) $pesanan->total_harga;
        
        $totalTerbayarSebelumnya = (int) \App\Models\Pemasukan::where('pesanan_id', $pesanan->id)->where('status', 'confirmed')->sum('jumlah');
        $kekuranganSebelumnya = $totalHarga - $totalTerbayarSebelumnya;
        
        $lunas       = $jumlahBayar >= $kekuranganSebelumnya;
        $labelTipe   = $this->paymentTipe === 'cod' ? 'COD' : 'Pembayaran Kantor';

        if ($lunas) {
            // === PEMBAYARAN PENUH ===
            $tglSekarang = now()->format('d M Y, H:i');
            $catatanLunas = "[Cicilan {$tglSekarang}]\n"
                . "Telah membayar: Rp" . number_format($jumlahBayar, 0, ',', '.') . "\n"
                . "telah lunas (dari total Rp" . number_format($totalHarga, 0, ',', '.') . ")";

            $catatanLama = $pesanan->catatan;
            $pesanan->update([
                'payment_status' => 'telah_dibayar',
                'paid_at'        => now(),
                'catatan'        => $catatanLama ? $catatanLama . "\n---\n" . $catatanLunas : $catatanLunas,
            ]);

            Pemasukan::create([
                'tanggal'    => today(),
                'pesanan_id' => $pesanan->id,
                'jumlah'     => $jumlahBayar,
                'keterangan' => "{$labelTipe} (Pelunasan): {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => Auth::id(),
            ]);

            $msg = 'Pembayaran lunas dikonfirmasi!';
            $actDesc = "{$labelTipe} lunas: #{$pesanan->nomor}";
        } else {
            // === CICILAN / PEMBAYARAN PARSIAL ===
            $kekuranganBaru = $kekuranganSebelumnya - $jumlahBayar;
            $tglSekarang    = now()->format('d M Y, H:i');
            $catatanCicilan = "[Cicilan {$tglSekarang}]"
                . "\nTelah membayar: Rp" . number_format($jumlahBayar, 0, ',', '.')
                . "\nMasih harus membayar: Rp" . number_format($kekuranganBaru, 0, ',', '.')
                . " (dari sisa kekurangan awal Rp" . number_format($kekuranganSebelumnya, 0, ',', '.') . ")"
                . " (dari total Rp" . number_format($totalHarga, 0, ',', '.') . ")";

            $catatanLama  = $pesanan->catatan;
            $pesanan->update([
                'catatan' => $catatanLama
                    ? $catatanLama . "\n---\n" . $catatanCicilan
                    : $catatanCicilan,
            ]);

            // Catat cicilan sebagai Pemasukan terpisah (bukan updateOrCreate)
            Pemasukan::create([
                'tanggal'    => today(),
                'pesanan_id' => $pesanan->id,
                'jumlah'     => $jumlahBayar,
                'keterangan' => "Cicilan {$labelTipe}: {$pesanan->nomor} ({$pesanan->nama})",
                'kategori'   => 'penjualan',
                'status'     => 'confirmed',
                'user_id'    => Auth::id(),
            ]);

            $msg     = 'Cicilan Rp' . number_format($jumlahBayar, 0, ',', '.') . ' dicatat. Kekurangan: Rp' . number_format($kekuranganBaru, 0, ',', '.');
            $actDesc = "Cicilan {$pesanan->nomor}: Rp" . number_format($jumlahBayar, 0, ',', '.');
        }

        Activity::create([
            'user_id'     => Auth::id(),
            'action'      => 'payment',
            'entity_type' => 'Pesanan',
            'entity_id'   => $pesanan->id,
            'description' => $actDesc,
        ]);

        $this->invalidatePesananCache();
        $this->closePaymentModal();
        session()->flash('success', $msg);
    }

    // Algoritma internal untuk mengkalkulasi harga otomatis secara dinamis
    // berdasarkan tipe pesanan (Carter/Sewa Harian, atau Beli Produk Satuan)
    private function hitungTotalHarga(PesananModel $pesanan): float|int
    {
        if (in_array($pesanan->tipe, ['carter', 'sewa'])) {
            return ($pesanan->durasi ?? 1) * config('pricing.sewa_per_day', 300000);
        }
        return $pesanan->jumlah * ($pesanan->produk->harga ?? 0);
    }

    public function deletePesanan($id)
    {
        $pesanan = PesananModel::findOrFail($id);
        $pesanan->delete();

        Activity::create([
            'user_id'     => $this->getUserId(),
            'action'      => 'delete',
            'entity_type' => 'Pesanan',
            'entity_id'   => $id,
            'description' => "Hapus: #{$pesanan->nomor}",
        ]);

        $this->invalidatePesananCache();
        session()->flash('success', 'Pesanan berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.admin.pesanan');
    }
}