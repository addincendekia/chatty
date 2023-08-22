<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['customer service', 'customer', 'seller'] as $roleName) {
            \App\Models\Role::create(['name' => $roleName]);
        }
    }
}
