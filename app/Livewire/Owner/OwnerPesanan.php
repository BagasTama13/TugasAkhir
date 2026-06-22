<?php

namespace App\Livewire\Owner;

use App\Livewire\Admin\Pesanan;
use App\Livewire\Traits\OwnerAccess;

// Class OwnerPesanan mewarisi keseluruhan sistem transaksi dari Admin\Pesanan (OOP Inheritance).
class OwnerPesanan extends Pesanan
{
    use OwnerAccess;

    public function mount(string $owner = ''): void
    {
        if (!empty($owner)) {
            $this->owner = strtolower($owner);
            $this->readonly = true;
            $this->ensureOwnerOnly();
        }
    }

    public function render()
    {
        return view('livewire.owner.pesanan');
    }

    public function toggleForm()
    {
        return;
    }

    public function closeForm()
    {
        return;
    }

    public function resetForm()
    {
        return;
    }

    // =========================================================================
    // OVERRIDE METHODS (Security & Role-Based Access Control)
    // Sebagian besar metode eksekusi diganti dengan abort(403) agar Owner hanya berstatus sebagai "Pengamat/Peninjau" (Read-Only)
    // Owner tidak bisa mengedit, menghapus, atau menerima pesanan layaknya Admin.
    // =========================================================================

    public function editPesanan($id)
    {
        abort(403, 'Owner users cannot modify pesanan.');
    }

    public function tambahPesanan()
    {
        abort(403, 'Owner users cannot create or update pesanan.');
    }

    public function acceptPesanan($id)
    {
        abort(403, 'Owner users cannot accept pesanan.');
    }

    public function rejectPesanan($id)
    {
        abort(403, 'Owner users cannot reject pesanan.');
    }

    public function markPerluDibayar($id)
    {
        abort(403, 'Owner users cannot mark pesanan as delivered.');
    }

    // Pengecualian: Owner mungkin sesekali diperbolehkan mengkonfirmasi uang yang masuk / tagihan dibayar
    // Oleh karena itu, method ini tetap memanggil logika bawaan parent (Admin)
    public function markTerbayar($id)
    {
        return parent::markTerbayar($id);
    }

    public function deletePesanan($id)
    {
        abort(403, 'Owner users cannot delete pesanan.');
    }
}
