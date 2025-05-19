<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'user_id' => 1,
                'username' => 'admin',
                'password' => 'admin123',
                'lvl_users' => 'admin',
                'verification_status' => 'verified'
            ],
            [
                'user_id' => 2,
                'username' => 'kepala_bengkel',
                'password' => 'bengkel123',
                'lvl_users' => 'kepala_bengkel',
                'verification_status' => 'verified'
            ],
            [
                'user_id' => 3,
                'username' => 'pdi',
                'password' => 'pdi123',
                'lvl_users' => 'pdi',
                'verification_status' => 'verified'
            ],
            [
                'user_id' => 4,
                'username' => 'satpam',
                'password' => 'satpam123',
                'lvl_users' => 'satpam',
                'verification_status' => 'verified'
            ]
        ];


        DB::table('users')->insert($users);
    }
}
