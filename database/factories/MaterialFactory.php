<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $state = ['good' , 'not good'];
        $typematerial = ['pc' , 'data show','imprimante','serveure','rallonge','routeur','switch'];

        return [
            'state' => $state[array_rand($state)],
            'serialnumber' => $this->faker->buildingNumber,
            'property' => $this->faker->text($maxNbChars = 5) ,
            'typematerial' => $typematerial[array_rand($typematerial)],
        ];
    }
}
