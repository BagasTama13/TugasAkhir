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

    public $search = '';
    public $startDate;
    public $endDate;
    public $statusFilter = '';

    public function mount(string $owner = ''): void
    {
        $user = Auth::user();
        $username = strtolower($user->username ?? '');

        // If owner parameter passed, this is for owner panel - handled by subclass
        if (!empty($owner) && !($this instanceof \App\Livewire\Owner\OwnerPemasukan)) {
            abort(403, 'Invalid access. Use owner panel instead.');
        }

        // Only check these if it's the base Admin\Pemasukan component
        if (!($this instanceof \App\Livewire\Owner\OwnerPemasukan)) {
            // Block owner and worker users from admin panel
            if (str_starts_with($username, 'owner') || str_starts_with($username, 'worker')) {
                abort(403, 'Access denied. Use your designated panel.');
            }

            // Only admin can access here
            if ($username !== 'admin') {
                abort(403, 'Unauthorized access.');
            }
        }

        // Default date range: current month
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    protected function getUserId(): int
    {
        return (int) Auth::id();
    }

    #[Computed]
    public function pemasukans()
    {
        $query = PemasukanModel::with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhere('kategori', 'like', '%' . $this->search . '%')
                  ->orWhere('catatan', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->startDate) {
            $query->whereDate('tanggal', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('tanggal', '<=', $this->endDate);
        }

        return $query->latest('tanggal')->get();
    }

    #[Computed]
    public function totalPemasukan()
    {
        // Sum of all delivered orders
        return \App\Models\Pesanan::where('status', 'delivered')->sum('total_harga');
    }

    #[Computed]
    public function pemasukaBulanIni()
    {
        // Sum of delivered orders in current month
        return \App\Models\Pesanan::where('status', 'delivered')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total_harga');
    }

    #[Computed]
    public function pemasukanPending()
    {
        // Sum of orders accepted by admin but not yet delivered
        return \App\Models\Pesanan::where('status', 'accepted')->sum('total_harga');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
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