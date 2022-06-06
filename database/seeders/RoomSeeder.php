<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roomname = ['td' , 'tp', 'amphi'];
        $roomcapacity = ['20','25','30','35','40','50'];
        $floor = ['0','1' , '2', '3','4'];
        for ($i=0; $i < 3; $i++) { 
            for ($j=0; $j < 15; $j++) { 
                Room::create([
                    'roomname' => $roomname[$i].$j,
                    'capacity' => $roomcapacity[array_rand($roomcapacity)],
                    'floor' => $floor[array_rand($floor)],    
                ]);
            }    
        }
    }
}
