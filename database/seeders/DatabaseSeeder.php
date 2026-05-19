<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin BPTrans',
            'username' => 'Admin',
            'email' => 'admin@bptrans.com',
            'password' => bcrypt('BPTrans'),
        ]);

        User::create([
            'name' => 'Owner Satu',
            'username' => 'owner1',
            'email' => 'owner1@bptrans.com',
            'password' => bcrypt('Owner123'),
        ]);

        User::create([
            'name' => 'Owner Dua',
            'username' => 'owner2',
            'email' => 'owner2@bptrans.com',
            'password' => bcrypt('Owner123'),
        ]);

        User::create([
            'name' => 'Owner Tiga',
            'username' => 'owner3',
            'email' => 'owner3@bptrans.com',
            'password' => bcrypt('Owner123'),
        ]);
    }
}
