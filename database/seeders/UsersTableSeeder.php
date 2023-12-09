<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'created_at'         => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'         => Carbon::now()->format('Y-m-d H:i:s'),
            'name'               => 'Admin',
            'email'              => 'admin@admin.com',
            'password'           => bcrypt('admin123'),
            'mobile_no'           => 1234567,
            'user_type'           => config('app.user_type.admin'),
            'status'             => 1 ]);
    }
}
