<?php
namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotice extends Component
{
    public $verified = false;

    public function mount()
    {
        $this->checkVerification();
    }

    public function refreshStatus()
    {
        $this->checkVerification();
    }

    protected function checkVerification()
    {
        $user = Auth::user();
        if ($user) {
            $this->verified = $user->hasVerifiedEmail();
        }
    }

    public function render()
    {
        return view('livewire.auth.email-verification-notice');
    }
}
