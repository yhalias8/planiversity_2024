<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('email', 'admin@gmail.com')->first();

        if (is_null($admin)) {

            $admin = new Admin();
            $admin->name = "Admin";
            $admin->email = "admin@gmail.com";
            $admin->password = Hash::make('admin123');
            $admin->mobile_no = "123456789";
            $admin->save();
        }
    }
}
