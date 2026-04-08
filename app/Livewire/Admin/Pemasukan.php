<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Livewire\Traits\OwnerAccess;
use App\Models\Pemasukan as PemasukanModel;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Pemasukan extends Component
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // If owner parameter passed, this is for owner panel - reject
        if (!empty($owner)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Block owner and worker users from admin panel
        if (in_array($username, ['owner', 'worker'], true)) {
            abort(403, 'Access denied. Use your designated panel.');
        }

        // Only admin can access here
        if ($username !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }
    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    #[Computed]
    public function pemasukans()
    {
        return PemasukanModel::with('user')->latest('tanggal')->get();
    }

    #[Computed]
    public function totalPemasukan()
    {
        return PemasukanModel::where('status', 'confirmed')->sum('jumlah');
    }

    #[Computed]
    public function pemasukaBulanIni()
    {
        return PemasukanModel::where('status', 'confirmed')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');
    }

    #[Computed]
    public function pemasukanPending()
    {
        return PemasukanModel::where('status', 'pending')->sum('jumlah');
    }

    public function confirmPemasukan($id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        $oldStatus = $pemasukan->status;

        $pemasukan->update(['status' => 'confirmed']);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'update',
            'entity_type' => 'Pemasukan',
            'entity_id' => $pemasukan->id,
            'description' => "Mengkonfirmasi pemasukan {$pemasukan->keterangan}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'confirmed'],
        ]);
    }

    public function rejectPemasukan($id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        $oldStatus = $pemasukan->status;

        $pemasukan->update(['status' => 'rejected']);

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'reject',
            'entity_type' => 'Pemasukan',
            'entity_id' => $pemasukan->id,
            'description' => "Menolak pemasukan {$pemasukan->keterangan}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'rejected'],
        ]);
    }

    public function deletePemasukan($id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        $keterangan = $pemasukan->keterangan;

        $pemasukan->delete();

        Activity::create([
            'user_id' => $this->getUserId(),
            'action' => 'delete',
            'entity_type' => 'Pemasukan',
            'entity_id' => $id,
            'description' => "Menghapus pemasukan {$keterangan}",
            'old_values' => [],
            'new_values' => [],
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pemasukan');
    }
}