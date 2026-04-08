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
        User::firstOrCreate(
            ['username' => 'Admin'],
            [
                'name' => 'Admin BPTrans',
                'email' => 'admin@bptrans.com',
                'password' => bcrypt('BPTrans'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'owner'],
            [
                'name' => 'Owner BPTrans',
                'email' => 'owner@bptrans.com',
                'password' => bcrypt('bptrans'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'worker'],
            [
                'name' => 'Worker BPTrans',
                'email' => 'worker@bptrans.com',
                'password' => bcrypt('bptrans'),
            ]
        );
    }
}
