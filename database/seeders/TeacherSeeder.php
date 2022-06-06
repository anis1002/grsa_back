<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50 ; $i++) { 
            
            $department = ['ifa' , 'tlsi'];
            $status = ['active', 'desactive'];
            $state = ['principale', 'secondary'];
            $grade = ['prof cour','prof td','prof tp'];
            $grade = ['prof cour','prof td','prof tp'];
            $phonenumber = ['34','12','56','78','90'];
            

            Teacher::create([
                'firstname' =>$name =  Str::random(4),
                'lastname' => Str::random(4),
                'phonenumber' => '06'.$phonenumber[array_rand($phonenumber)].$phonenumber[array_rand($phonenumber)].$phonenumber[array_rand($phonenumber)].$phonenumber[array_rand($phonenumber)],
                'department' => $department[array_rand($department)],
                'grade' => $grade[array_rand($grade)],
                'status' => $status[array_rand($status)],
                'state' => $state[array_rand($state)],
                'email' =>$email =  Str::random(4).'@gmail.com',
                 'password' =>$password = Hash::make('12345678'), // password
        ]);
            
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->save();
        }
    }
}
