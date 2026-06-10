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
        $admin = User::updateOrCreate([
            'username' => 'Admin',
        ], [
            'name' => 'Admin BPTrans',
            'email' => 'admin@bptrans.com',
            'password' => bcrypt('BPTrans'),
        ]);

        if (! $admin->hasVerifiedEmail()) {
            $admin->markEmailAsVerified();
        }

        $owner = User::updateOrCreate([
            'username' => 'owner',
        ], [
            'name' => 'Owner BPTrans',
            'email' => 'owner@bptrans.com',
            'password' => bcrypt('bptrans'),
        ]);

        if (! $owner->hasVerifiedEmail()) {
            $owner->markEmailAsVerified();
        }

        $worker = User::updateOrCreate([
            'username' => 'worker',
        ], [
            'name' => 'Worker BPTrans',
            'email' => 'worker@bptrans.com',
            'password' => bcrypt('bptrans'),
        ]);

        if (! $worker->hasVerifiedEmail()) {
            $worker->markEmailAsVerified();
        }

        $wamilo = User::updateOrCreate([
            'username' => 'wamilo',
        ], [
            'name' => 'Wamilo User',
            'email' => 'wamilo@bptrans.com',
            'password' => bcrypt('wamilo123'),
            'alamat' => 'Jl. Test Wamilo No. 1',
            'no_hp' => '081234567890',
        ]);

        if (! $wamilo->hasVerifiedEmail()) {
            $wamilo->markEmailAsVerified();
        }
    }
}
