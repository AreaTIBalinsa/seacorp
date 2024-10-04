<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Edinson Santos Vilchez',
            'email' => 'sanvil@balinsa.net',
            'password' => bcrypt('123456789'),
        ])->assignRole('Administradores');
        
    }
}
