<?php

namespace Database\Seeders;

use App\Models\Prsnadministrative;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PrsnAdministrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'AdministrativePerson',
            'email' => 'AdministrativePerson@gmail.com',
            'role' => 'AdministrativePerson',
            'password' => Hash::make('12345678')
        ]);

        Prsnadministrative::create([
            'firstname' => 'AdministrativePerson',
            'lastname' => 'AdministrativePerson',
            'email' => 'AdministrativePerson@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
