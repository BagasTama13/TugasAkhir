<?php
namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

// Komponen Livewire untuk menampilkan notifikasi verifikasi email.
// Digunakan untuk mengecek apakah user sudah melakukan verifikasi email atau belum secara realtime.
class EmailVerificationNotice extends Component
{
    // Properti publik $verified akan ter-binding dengan view (blade).
    public $verified = false;

    // Method mount() dipanggil pertama kali saat komponen Livewire di-load (mirip constructor).
    public function mount()
    {
        $this->checkVerification();
    }

    // Method ini bisa dipanggil dari frontend (misalnya via tombol refresh atau polling) untuk mengecek ulang status.
    public function refreshStatus()
    {
        $this->checkVerification();
    }

    // Logika utama untuk mengecek status verifikasi email dari user yang sedang login.
    protected function checkVerification()
    {
        $user = Auth::user();
        if ($user) {
            // hasVerifiedEmail() adalah bawaan Laravel MustVerifyEmail interface.
            $this->verified = $user->hasVerifiedEmail();
        }
    }

    // Method render() mengembalikan view blade yang akan dirender ke browser.
    public function render()
    {
        return view('livewire.auth.email-verification-notice');
    }
}
