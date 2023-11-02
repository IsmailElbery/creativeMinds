<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'ismail',
                'mobile' => '01125570513',
                'active' => true,
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Jane Smith',
                'mobile' => '9876543210',
                'active' => false,
                'password' => Hash::make('12356'),
            ],
            // Add more user data as needed
        ];

        // Insert the users into the database
        DB::table('users')->insert($users);
    }
}
