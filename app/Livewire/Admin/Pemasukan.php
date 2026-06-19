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

    public $exportMonth;
    public $exportYear;
    public $exportFormat = 'pdf';

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

        $this->exportMonth = now()->format('m');
        $this->exportYear = now()->format('Y');
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
        // Sum of all paid orders
        return \App\Models\Pesanan::where('payment_status', 'telah_dibayar')->sum('total_harga');
    }

    #[Computed]
    public function pemasukaBulanIni()
    {
        // Sum of paid orders in current month
        return \App\Models\Pesanan::where('payment_status', 'telah_dibayar')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total_harga');
    }

    #[Computed]
    public function pemasukanPending()
    {
        // Sum of orders in queue, processing, or delivered but not yet paid
        return \App\Models\Pesanan::where('payment_status', 'belum_dibayar')
            ->whereIn('status', ['dalam_antrian', 'diproses', 'terkirim'])
            ->sum('total_harga');
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

        $pemasukan->update([
            'status' => 'confirmed',
            'user_id' => $this->getUserId()
        ]);

        if ($pemasukan->pesanan_id) {
            $pesanan = \App\Models\Pesanan::find($pemasukan->pesanan_id);
            if ($pesanan && $pesanan->payment_status !== 'telah_dibayar') {
                $pesanan->update([
                    'payment_status' => 'telah_dibayar',
                    'paid_at' => now(),
                ]);
            }
        }

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

        $pemasukan->update([
            'status' => 'rejected',
            'user_id' => $this->getUserId()
        ]);

        if ($pemasukan->pesanan_id) {
            $pesanan = \App\Models\Pesanan::find($pemasukan->pesanan_id);
            if ($pesanan && $pesanan->status !== 'rejected') {
                $pesanan->update(['status' => 'rejected']);
            }
        }

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

    public function exportData()
    {
        $this->validate([
            'exportMonth' => 'required|numeric|min:1|max:12',
            'exportYear' => 'required|numeric|min:2000|max:' . (date('Y') + 5),
            'exportFormat' => 'required|in:excel,word,pdf',
        ]);

        $data = PemasukanModel::with('user')
            ->whereMonth('tanggal', $this->exportMonth)
            ->whereYear('tanggal', $this->exportYear)
            ->where('status', 'confirmed') // Only confirmed income
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $data->sum('jumlah');
        \Carbon\Carbon::setLocale('id');
        $monthName = \Carbon\Carbon::createFromDate($this->exportYear, $this->exportMonth, 1)->translatedFormat('F Y');

        if ($this->exportFormat === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.pemasukan-pdf', [
                'data' => $data,
                'totalPemasukan' => $totalPemasukan,
                'monthName' => $monthName,
                'format' => 'pdf'
            ]);
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, "Laporan_Pemasukan_{$monthName}.pdf");
        } 
        elseif ($this->exportFormat === 'word') {
            $html = view('exports.pemasukan-pdf', [
                'data' => $data,
                'totalPemasukan' => $totalPemasukan,
                'monthName' => $monthName,
                'format' => 'word'
            ])->render();

            return response()->streamDownload(function () use ($html) {
                echo $html;
            }, "Laporan_Pemasukan_{$monthName}.doc", [
                'Content-Type' => 'application/vnd.ms-word',
            ]);
        }
        elseif ($this->exportFormat === 'excel') {
            return response()->streamDownload(function () use ($data, $totalPemasukan, $monthName) {
                $file = fopen('php://output', 'w');
                // Use semi-colon for Excel compatibility in some regions, or comma
                fputcsv($file, ['Laporan Pemasukan: ' . $monthName]);
                fputcsv($file, []);
                fputcsv($file, ['No', 'Tanggal', 'Keterangan', 'Kategori', 'Jumlah', 'Petugas']);
                
                $no = 1;
                foreach ($data as $row) {
                    fputcsv($file, [
                        $no++,
                        $row->tanggal->format('d/m/Y'),
                        $row->keterangan,
                        $row->kategori,
                        $row->jumlah,
                        $row->user ? $row->user->name : '-'
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, ['', '', '', 'Total Pendapatan', $totalPemasukan, '']);
                fclose($file);
            }, "Laporan_Pemasukan_{$monthName}.csv", [
                'Content-Type' => 'text/csv',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.pemasukan');
    }
}