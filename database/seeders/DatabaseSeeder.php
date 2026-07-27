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
        // Seeding roles
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $ownerRole = \App\Models\Role::firstOrCreate(['name' => 'owner']);
        $workerRole = \App\Models\Role::firstOrCreate(['name' => 'worker']);
        $userRole = \App\Models\Role::firstOrCreate(['name' => 'user']);

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
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

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
        $owner->roles()->syncWithoutDetaching([$ownerRole->id]);

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
        $worker->roles()->syncWithoutDetaching([$workerRole->id]);

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
        $wamilo->roles()->syncWithoutDetaching([$userRole->id]);
    }
}
