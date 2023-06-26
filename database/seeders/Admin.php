<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Phyoe Zaw Aung',
            'email'=>'scm.phyoezawaung@gmail.com',
            'password'=>Hash::make('123456'),
            'profile'=>'profile',
            'type'=>0,
            'create_user_id'=>1,
            'updated_user_id'=>1
        ]);
    }
}
