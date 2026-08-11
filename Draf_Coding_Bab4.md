**3. Implementasi Pengodean (Coding)**

Tahap implementasi pengodean merupakan proses transformasi seluruh rancangan arsitektur, pemodelan basis data, dan *mockup* antarmuka menjadi perangkat lunak fungsional. Proses komputasi *backend* dieksekusi menggunakan bahasa pemrograman PHP dengan kerangka kerja (*framework*) Laravel, yang dipadukan dengan MySQL sebagai sistem manajemen basis data relasionalnya. Sementara itu, interaktivitas antarmuka dan penataan visual (*styling*) dibangun secara reaktif menggunakan pustaka *Livewire* dan *Tailwind CSS*. Berdasarkan pedoman metodologi *Extreme Programming* (XP) yang telah direncanakan sebelumnya, pelaksanaan tahap pengodean ini dideskripsikan sebagai berikut:

**1) Pengodean Berdasarkan Modul Prioritas Iterasi**
Sejalan dengan jadwal (*Release Plan*) pada fase perencanaan, proses penulisan kode sumber (*source code*) tidak dilakukan secara acak atau serentak, melainkan dikerjakan secara bertahap dan terfokus pada fungsionalitas di setiap siklus iterasi yang sedang aktif. 

1.  **Implementasi Iterasi 1 (Arsitektur & Manajemen Produk):** Pengodean dimulai dengan membangun fondasi sistem keamanan, meliputi pembuatan migrasi tabel pengguna (*users*), penentuan peran (*roles*), dan logika *Role-Based Access Control* (RBAC) pada *controller*. Setelah sistem autentikasi berjalan stabil, penulisan kode dilanjutkan dengan pembuatan komponen *Livewire* untuk fungsi *Create, Read, Update, Delete* (CRUD) pada modul katalog etalase produk (seperti kayu dan bahan bangunan lainnya) yang dioperasikan oleh Admin.
2.  **Implementasi Iterasi 2 (Transaksi Bisnis & Integrasi):** Setelah fondasi siap, pengodean difokuskan pada logika inti bisnis BP Trans. Proses ini mencakup pembuatan komponen antarmuka *Direct Order* (pemesanan tanpa keranjang belanja) dan penulisan algoritma *auto-generate ID* resi pesanan. Selanjutnya, dilakukan pengodean logika komunikasi data (*Application Programming Interface* / API) untuk mengintegrasikan gerbang pembayaran (*Payment Gateway*) Midtrans, serta implementasi pembaruan status logistik oleh aktor Pekerja (*Worker*).
3.  **Implementasi Iterasi 3 (Manajerial & Evaluasi):** Pada siklus terakhir, pengodean dialokasikan untuk menyempurnakan fitur pengawasan dan rekapitulasi data. Komponen yang dikembangkan meliputi perancangan tata letak antarmuka dasbor untuk setiap aktor, penulisan fungsi komputasi untuk mengekspor (mengunduh) laporan arus kas perusahaan, serta pengodean modul pencatatan jejak riwayat aktivitas (*Activity Log*) secara menyeluruh guna menjamin transparansi sistem sebelum perangkat lunak digunakan di lingkungan produksi (*live*).

**2) Penulisan Kode yang Bersih dan Standar (Coding Standards)**
Untuk memastikan tingkat keterbacaan (*readability*) dan kemudahan pemeliharaan sistem di masa mendatang (*maintainability*), pengodean sistem BP Trans secara ketat menerapkan pedoman kode yang bersih (*Clean Code*). Penamaan kelas, fungsi, dan variabel pada aplikasi ini ditulis menggunakan standar penamaan *CamelCase* yang deskriptif dan mencerminkan fungsionalitas aslinya secara lugas. 

Sebagai implementasi tambahan dari *Extreme Programming* (XP), setiap blok logika komputasi yang memiliki tingkat kompleksitas tinggi diwajibkan untuk didokumentasikan melalui penambahan komentar internal pada kode sumber (*inline comments*). Salah satu pembuktian penerapan standar penulisan ini direpresentasikan pada fungsi penanganan notifikasi balik (*webhook callback*) dari layanan *Payment Gateway* Midtrans. Penulis memisahkan algoritma pemrosesan status transaksi, pengecekan keamanan tanda tangan digital (*Signature Key*), dan rekonsiliasi data pesanan secara terstruktur agar alur komunikasi data dengan server pihak ketiga (Midtrans) dapat dengan mudah ditelusuri dan dimodifikasi kelak jika diperlukan.

**Kode Snippet 1. Penerapan Standar Pengodean (Clean Code) pada Logika Webhook Pembayaran**
```php
<?php
// ... [Deklarasi Namespace dan Class] ...
    
    public function handleNotification(Request $request)
    {
        try {
            $data = $this->midtransService->parseNotification();

            // Pengecekan Keamanan: Memvalidasi Midtrans Signature Key 
            if (empty($data['is_valid_signature'])) {
                return response()->json(['message' => 'Invalid signature key'], 403);
            }

            // ... [Ekstraksi data order_id dan transaction_status] ...
            
            // Memperbarui status pesanan menjadi Lunas secara dinamis
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                $this->markAsPaid($pesanan, $transactionId);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $pesanan->update(['snap_token' => null]);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            // ... [Penanganan Error Logging] ...
        }
    }
```

**3) Pengodean secara Modular dan Integrasi Terus-Menerus (Continuous Integration)**
Untuk mengimplementasikan prinsip desain modular, antarmuka visual (*View*) dan logika pemrograman bisnis (*Controller*) dipecah menjadi unit-unit komponen *Livewire* yang berdiri sendiri secara independen. Pendekatan isolasi logika ini dibuktikan melalui pemisahan struktur hierarki direktori sistem yang membagi komponen berdasarkan empat (4) porsi kewenangan (*roles*) utama, yaitu:

*   `app/Livewire/Admin/` (Berisi komponen modular khusus Admin seperti Pesanan, Etalase)
*   `app/Livewire/Owner/` (Berisi komponen pelaporan makro khusus Pemilik)
*   `app/Livewire/User/` (Berisi komponen transaksi esensial pelanggan)
*   `app/Livewire/Worker/` (Berisi komponen pembaruan status logistik khusus Pekerja)

Pemisahan secara arsitektural ini secara langsung memfasilitasi kelancaran siklus Integrasi Terus-Menerus (*Continuous Integration*). Dengan cara ini, setiap pembaruan kode, perbaikan *bug*, atau penambahan komponen baru yang dieksekusi pada iterasi tertentu dapat langsung diintegrasikan ke dalam repositori utama dan diuji kemampuannya secara terisolasi. Hal ini mengeliminasi risiko kerusakan fungsi (konflik kode) pada komponen-komponen penyusun lain yang sudah terintegrasi sebelumnya.

*(Gambar Screenshot Struktur Folder app/Livewire diletakkan di sini)*

**4) Restrukturisasi Kode (Refactoring)**
Proses *refactoring* dilakukan secara berkesinambungan di sela-sela fase pengodean setiap iterasi. Seperti yang telah dijabarkan secara rinci pada sub-bab *Desain Refaktoring* sebelumnya, penulis secara berkala mengeksekusi penyederhanaan logika (*code cleanup*). 

Pembuktian teknis dari langkah *refactoring* ini meliputi penerapan *OOP Inheritance* (pewarisan kelas) pada komponen antarmuka Dasbor antar-aktor dan pengenkapsulasian logika keamanan ke dalam bentuk modular *Traits* (seperti modul `OwnerAccess`). Serangkaian langkah restrukturisasi ini membuktikan bahwa perangkat lunak yang dihasilkan di BP Trans dapat terhindar dari tumpukan kerumitan kode (*technical debt*) serta menjaga efisiensi kinerja server, meskipun terdapat penambahan beban fitur secara masif dari satu iterasi ke iterasi berikutnya.

**Kode Snippet 2. Implementasi Inheritance (Pewarisan) pada Komponen Dasbor**
```php
<?php
namespace App\Livewire\Owner;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Traits\OwnerAccess;

// Mewarisi seluruh logika dari kelas Admin\Dashboard
class OwnerDashboard extends Dashboard
{
    use OwnerAccess; // Menyuntikkan modul Trait keamanan

    public function mount(string $owner = '', string $worker = ''): void
    {
        parent::mount($owner, $worker); // Memanggil fungsi induk
        
        if (!empty($owner)) {
            $this->owner = strtolower($owner);
            $this->readonly = true; // Mengubah atribut state untuk Owner
            $this->ensureOwnerOnly();
        }
    }
}
```

**Kode Snippet 3. Enkapsulasi Logika Keamanan Menggunakan Modul Trait**
```php
<?php
namespace App\Livewire\Traits;
use Illuminate\Support\Facades\Auth;

trait OwnerAccess
{
    // Deklarasi variabel statis untuk membatasi antarmuka (read-only)
    public bool $readonly = false;
    public string $owner = '';

    // Fungsi pusat untuk memeriksa peran 'owner'
    public function isOwnerUser(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole('owner');
    }

    // ... [Fungsi blokir akses ensureAdminOnly dan ensureOwnerOnly] ...
}
```
