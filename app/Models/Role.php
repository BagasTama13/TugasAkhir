<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model Role digunakan untuk mengatur hak akses (peran) dari setiap User (misal: Admin, User Biasa).
class Role extends Authenticatable
{
    // $fillable menentukan kolom yang bisa diisi secara massal.
    protected $fillable = ['name']; // Menyimpan nama role, contoh: 'admin', 'user'

    /**
     * Users that belong to this role.
     * Relasi Many-to-Many: 1 Role bisa dimiliki oleh banyak User, dan sebaliknya (tergantung desain database pivot).
     * Biasanya menggunakan tabel pivot seperti 'role_user'.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
