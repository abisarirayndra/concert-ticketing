<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::updateOrCreate(
            ['user_email' => 'admin@gmail.com'],
            [
                'user_name' => 'Admin',
                'user_name_last' => 'Utama',
                'user_email' => 'admin@gmail.com',
                'user_password' => Hash::make('12345678'),
                'user_role' => 1,
                'user_status' => 1,
            ]
        );
    }
}
