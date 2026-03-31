<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Pemasukan as PemasukanModel;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class Pemasukan extends Component
{
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
        return view('livewire.pemasukan');
    }
}