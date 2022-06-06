<?php

namespace Database\Seeders;

use App\Models\Timing;
use Illuminate\Database\Seeder;

class TimingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timing::create([
            'roomtiming' => '08',
            'starttime' => '08:00',
            'endtime' => '10:00',
        ]);
        Timing::create([
            'roomtiming' => '10',
            'starttime' => '10:00',
            'endtime' => '12:00',
        ]);
        Timing::create([
            'roomtiming' => '12',
            'starttime' => '12:00',
            'endtime' => '14:00',
        ]);
        Timing::create([
            'roomtiming' => '14',
            'starttime' => '14:00',
            'endtime' => '16:00',
        ]);
    }
}
