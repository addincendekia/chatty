<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all()->pluck('id')->toArray();

        User::factory(20)->create()->each(function (User $user) use ($roles): void {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => Arr::random($roles),
            ]);
        });
    }
}
