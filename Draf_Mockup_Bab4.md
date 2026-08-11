**c. Hasil Perancangan Antarmuka Pengguna (Mockup / UI Design)**

Sebagai visualisasi awal dan pedoman visualisasi antarmuka aplikasi, peneliti membuat rancangan *Mockup* (Purwarupa Tampilan). *Mockup* dirancang sebagai representasi statis beresolusi tinggi (*high-fidelity*) untuk setiap halaman aplikasi yang disesuaikan dengan kebutuhan masing-masing hak akses pengguna (*User, Admin, Worker, dan Owner*). 

Pembuatan *mockup* ini memiliki peran krusial sebagai media validasi ekspektasi fitur antara peneliti dan pemangku kepentingan (*stakeholders*) sebelum kode program ditulis. Di samping itu, dengan berpegang pada prinsip *Simple Design* dalam metodologi *Extreme Programming* (XP), rancangan antarmuka disusun secara terpusat untuk meminimalisasi kompleksitas navigasi. Pendekatan ini mengutamakan pembuatan tata letak (*layout*) yang lugas, hierarki informasi yang terstruktur, serta elemen navigasi yang intuitif guna memastikan seluruh fungsionalitas (*User Stories*) dapat diakses secara optimal dan responsif.

### 1) Rancangan Antarmuka Pelanggan (User)

**a. Mockup Halaman Beranda dan Etalase (User Dashboard)**
Halaman beranda yang terintegrasi dengan antarmuka etalase merupakan representasi *high-fidelity* dari modul utama yang pertama kali diakses oleh pelanggan setelah proses autentikasi berhasil dilakukan. Antarmuka ini dirancang untuk memvalidasi ekspektasi pemangku kepentingan terhadap kemudahan pelanggan dalam mengeksplorasi ketersediaan produk bangunan.

*(Sisipkan Gambar Mockup Etalase di sini)*
**Gambar X. Rancangan Antarmuka Halaman Beranda dan Etalase Pelanggan**

Elemen-elemen fungsional yang divisualisasikan pada rancangan antarmuka ini meliputi:
1. **Bagian Kepala (Header) dan Indikator Pesanan:** Komponen yang menampilkan identitas sesi pengguna secara personal dan menyediakan pintasan navigasi menuju halaman riwayat pesanan (Pesanan Saya). Pada pintasan tersebut, disematkan sebuah indikator numerik (*badge*) guna menginformasikan jumlah pesanan yang sedang aktif atau diproses.
2. **Fitur Penyaringan Kategori:** Sekumpulan tombol filter (seperti Bahan Bakar, Sewa Mobil, dan Bahan Bangunan) yang berfungsi merespons instruksi penyortiran dari pengguna secara interaktif, memungkinkan pencarian produk yang lebih spesifik.
3. **Katalog Produk (Grid Layout):** Representasi entitas data produk dari basis data ke dalam bentuk tata letak *grid* yang responsif. Setiap unit kartu produk merangkum informasi esensial berupa representasi foto (*image*), spesifikasi harga, penamaan produk, dan deskripsi singkat.
4. **Tombol Eksekusi Transaksi:** Penempatan tombol "Pesan Sekarang" pada setiap kartu produk merupakan implementasi tata letak dari alur *Direct Order*. Tombol ini dirancang sebagai pemicu perpindahan halaman (routing) menuju formulir pemesanan.

**b. Mockup Halaman Formulir Pemesanan (Direct Order)**
Halaman formulir pemesanan merupakan purwarupa antarmuka interaktif yang dirancang untuk memfasilitasi siklus transaksi secara langsung antara pelanggan dan sistem (*Direct Order*), tanpa mengikutsertakan mekanisme keranjang belanja.

*(Sisipkan Gambar Mockup Form Pesanan di sini)*
**Gambar Y. Rancangan Antarmuka Halaman Formulir Pemesanan**

Untuk memvalidasi kemudahan operasional pemesanan, komponen *high-fidelity* pada rancangan halaman ini disusun atas:
1. **Mekanisme Pengisian Otomatis (Auto-fill):** Atribut *input* dasar seperti Nama Pembeli, Nomor WhatsApp, dan Alamat Pengiriman dirancang untuk menarik data secara otomatis dari profil autentikasi sesi pengguna saat itu guna meminimalisasi redudansi pengetikan.
2. **Formulir Kuantitas dan Logistik:** Komponen masukan (*input field*) interaktif tempat pelanggan menetapkan jumlah barang (*quantity*) yang akan dibeli serta instruksi operasional untuk pengiriman. Pada modul ini, visualisasi subtotal harga akan diakumulasikan secara dinamis berdasarkan nilai kuantitas tersebut.
3. **Tombol Penyimpanan Transaksi:** Elemen antarmuka penentu (Call-to-Action) yang berfungsi mengeksekusi validasi pesanan, membangkitkan nomor resi unik, merekam data ke basis data dengan status transaksi '*pending*', serta mencatat rekam jejak pada Log Aktivitas (*Activity Log*).

**c. Mockup Halaman Riwayat Pesanan (Pesanan Saya) & Pembayaran**
Halaman riwayat pesanan bertindak sebagai pusat pemantauan (monitoring) bagi pelanggan untuk melacak status transaksi secara mandiri. Rancangan antarmuka pada halaman ini dirancang sangat krusial karena memuat alur integrasi pembayaran digital (*Payment Gateway*).

*(Sisipkan Gambar Mockup Halaman Riwayat Pesanan di sini)*
**Gambar Z. Rancangan Antarmuka Halaman Riwayat Pesanan dan Jendela Pembayaran**

Rancangan antarmuka *high-fidelity* pada halaman ini mencakup:
1. **Daftar Rekapitulasi Pesanan:** Visualisasi antarmuka dalam bentuk daftar atau tabel (*list*) yang menampilkan seluruh histori transaksi yang pernah dilakukan pelanggan, dilengkapi dengan status logistik terkini (misalnya: 'Dalam Antrian', 'Diproses', atau 'Terkirim'). Data disajikan menggunakan sistem paginasi (*pagination*) guna menjaga efisiensi pemuatan halaman.
2. **Jendela Detail Transaksi:** Komponen *pop-up* (modal) interaktif yang memberikan rincian tagihan secara menyeluruh jika pelanggan memilih satu entri pesanan spesifik.
3. **Integrasi Antarmuka Payment Gateway:** Fitur paling vital pada halaman ini adalah penyediaan tombol "Bayar Sekarang" untuk pesanan yang berstatus belum lunas. Tombol ini didesain sebagai jembatan (*trigger*) pemanggilan antarmuka *Snap Pop-up* Midtrans secara *asinkron*. Antarmuka pihak ketiga tersebut memvisualisasikan opsi saluran pembayaran yang dapat dipilih oleh pelanggan, yang kemudian akan memicu pengiriman notifikasi pembaruan status pelunasan (*webhook callback*) kepada sistem.

**d. Mockup Halaman Manajemen Profil Pelanggan**
Halaman profil adalah rancangan antarmuka yang difungsikan untuk memfasilitasi kebutuhan pengguna (*User Stories*) terkait pengelolaan identitas akun secara otonom.

*(Sisipkan Gambar Mockup Halaman Profil di sini)*
**Gambar W. Rancangan Antarmuka Halaman Manajemen Profil Pelanggan**

Elemen penyusun antarmuka halaman ini meliputi:
1. **Visualisasi Data Profil:** Komponen yang menyajikan rangkuman informasi esensial pelanggan yang terdaftar di basis data, seperti Nama Lengkap, Nomor Kontak (WhatsApp), dan alamat Surel (*Email*).
2. **Formulir Pembaruan Data:** Antarmuka interaktif yang mengakomodasi operasi pengubahan atau pembaruan (*Update*) data profil dan kredensial keamanan (kata sandi) pengguna. Atribut *input* pada form ini saling terhubung secara dua arah (*two-way binding*) agar perubahan informasi, terutama perihal alamat pengiriman logistik, dapat senantiasa tervalidasi dan mutakhir (*up-to-date*).
