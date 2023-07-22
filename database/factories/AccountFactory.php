<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $code =  Str::random(10);
        return [
            'name'=>$this->faker->firstNameMale(),
            'email'=>$this->faker->unique()->email,
            'password'=>'password',
            'lab_name'=>$this->faker->realTextBetween(10,30),
            'address'=>$this->faker->realTextBetween(20,30),
            'phone'=>$this->faker->phoneNumber(),
            'code'=>$code,

        ];
    }
}
