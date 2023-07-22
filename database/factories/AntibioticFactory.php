<?php

namespace Database\Factories;

use App\Models\Analyz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Antibiotic>
 */
class AntibioticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $Antibiotic=['test1','test2','test3'];
        return [
            'subject'=>'subject',
            'analyz_id'=>Analyz::inRandomOrder()->get()->value('id'),
        ];
    }
}
