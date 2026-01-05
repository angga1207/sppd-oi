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
        // Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Kepala OPD', 'slug' => 'kepala-opd'],
            ['name' => 'Staff', 'slug' => 'staff'],
        ];

        foreach ($roles as $role) {
            if (\App\Models\Role::where('slug', $role['slug'])->exists()) {
                continue;
            }
            \App\Models\Role::create($role);
        }

        // make developer user
        if (!User::where('email', 'developer@sppd.com')->exists()) {
            $user = User::create([
                'name' => 'Developer',
                'email' => 'developer@sppd.com',
                'username' => 'developer',
                'image' => '/storage/images/users/default.png',
                'role_id' => 1,
                'instance_id' => null,
                'jabatan' => null,
                'no_hp' => null,
                'password' => bcrypt('arungboto'),
            ]);
        }

        // Run seeders
        $this->call([
            InstanceSeeder::class,
        ]);
    }
}
