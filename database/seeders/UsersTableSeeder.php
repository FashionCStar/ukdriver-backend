<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'tony',
            'mobile' => '1231231231',
            'password' => bcrypt('admin'),
            'email' => 'tony_admin@laravel.com'
        ]);
    }
}
