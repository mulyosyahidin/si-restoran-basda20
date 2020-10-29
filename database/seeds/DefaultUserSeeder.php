<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Admin Super';
        $user->email = 'martinms.za@gmail.com';
        $user->password = Hash::make('12345');
        $user->created_at = date('Y-m-d H:i:s');
        $user->save();

        $user->assignRole('admin');
    }
}
