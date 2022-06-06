<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            PrsnAdministrativeSeeder::class,
            MaterialSeeder::class,
            RoomSeeder::class,
            TeacherSeeder::class,
            TimingSeeder::class,
        ]);
    }
}
