<?php

namespace App\Services;

use App\Models\Section;

trait BuildAccount
{

    public function genders($Account, $genders)
    {
        foreach ($genders as $gender) {

            $Account->Genders()->create([
                'name' => $gender->name,
            ]);
        }
    }
    public function testUnits($Account, $testUnits)
    {
        foreach ($testUnits as $units) {

            $Account->testUnit()->create([
                'unit' => $units->unit,
            ]);
        }
    }
    public function testMethods($Account, $testMethods)
    {
        foreach ($testMethods as $testMethods) {

            $Account->testMethod()->create([
                'test_method' => $testMethods->test_method,
            ]);
        }
    }
    public function sections($Account, $sections)
    {
        foreach ($sections as $data) {


            $adminAnti = $data->antibioticSections;
            $sectionCreated = $Account->Section()->create([
                'name' => $data['name'],
                'test_code' => $data['test_code'],
                'test_print_name' => $data['test_print_name'],
                'price_for_patient' => $data['price_for_patient'],
                'price_for_lap' => $data['price_for_lap'],
                'price_for_company' => $data['price_for_company'],
                'class_report' => $data['class_report'],
                'admin_tupe_id' => $data['admin_tupe_id'],
                'test_method_id' => $data['test_method_id'],
                'test_unit_id' => $data['test_unit_id'],
                'once' => $data['once'],
                'type' => $data['type'],
                'antibiotic' => $data['antibiotic'],
                'role' => Section::ACCOUNT,


            ]);
            foreach ($adminAnti as $Anti) {

                $sectionCreated->antibioticSections()->create(['subject' => $Anti['subject']]);
            }

            foreach ($data->analyz as $analys_data) {
                $adminAnalysAnti = $analys_data->antibiotics;

                $analysCreated = $Account->analyz()->create([

                    'test_code' => $analys_data['test_code'],
                    'test_print_name' => $analys_data['test_print_name'],
                    'price_for_patient' => $analys_data['price_for_patient'],
                    'price_for_lap' => $analys_data['price_for_lap'],
                    'price_for_company' => $analys_data['price_for_company'],
                    'class_report' => $analys_data['class_report'],
                    'section_id' => $analys_data['section_id'],
                    'admin_tupe_id' => $analys_data['admin_tupe_id'],
                    'test_method_id' => $analys_data['test_method_id'],
                    'test_unit_id' => $analys_data['test_unit_id'],
                    'once' => $analys_data['once'],
                    'antibiotic' => $analys_data['antibiotic'],
                ]);
                foreach ($adminAnalysAnti as $analysAnti) {

                    $analysCreated->antibiotics()->create(['subject' => $analysAnti['subject']]);
                }
            }
        }
    }
}
