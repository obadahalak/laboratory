<?php

namespace Database\Factories;

use App\Models\adminTupe;
use App\Models\TestMethod;
use App\Models\TestUnit;
use Delight\Random\Random;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analyz>
 */


class AnalyzFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $hsitopology = [
            'hsitopology' => ['text' => $this->faker->realText(60)]
            ///just one
        ];

        $maneReport = [
            'mane_report' => [

                ['normal_range' => $this->faker->realText(50), 'gender' => 'male', 'H' => 100, 'L' => 40],
                ['normal_range' => $this->faker->realText(50), 'gender' => 'female', 'H' => 200, 'L' => 50],
                ['normal_range' => $this->faker->realText(50), 'gender' => 'male', 'H' => 400, 'L' => 100],
            ]
        ];


        $cultureReport = [
            'culture_report' =>
            [
                ['text' => $this->faker->realText(60)],
                ['text' => $this->faker->realText(60)],
                ['text' => $this->faker->realText(60)],
            ]
        ];

        $classRport=[$cultureReport,$maneReport,$hsitopology];
        return [
            'test_code' => Random::alphanumericHumanString(12),
            'test_print_name' => $this->faker->text(10),
            'price_for_patient' => \random_int(100, 8000),
            'price_for_lap' => \random_int(100, 8000),
            'price_for_company' => \random_int(100, 8000),
            // 'test_unit'=>$this->faker->text(10),
            'once' => true,
            'antibiotic' => true,
            'class_report'=>$classRport[random_int(0,2)],
            'test_method_id' => TestMethod::inRandomOrder()->get()->value('id'),
            'test_unit_id' => TestUnit::inRandomOrder()->get()->value('id'),
            'admin_tupe_id'=>adminTupe::inRandomOrder()->get()->value('id'),
            'section_id' => 1,

        ];
    }
}
