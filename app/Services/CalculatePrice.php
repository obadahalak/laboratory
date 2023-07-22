<?php

namespace App\Services;

use App\Models\Section;

trait CalculatePrice
{

    public function getPrice($patieonAnalys){

    }
    public function doctor($data, $patieonAnalys)
    {
        $price = $data['price_doctor'] * $data['ratio_price'] / 100  + $data['price_doctor'];

        $patieonAnalys->price = $price;

        $patieonAnalys->save();
        return true;
    }
    public function company($data , $patieonAnalys)
    {
        $price = $data['price_company'];
        $patieonAnalys->price = $price;
        $patieonAnalys->save();
        return true;
    }
    public function lab($data,$patieonAnalys)
    {
        $price = $data['price_lab'];
        $patieonAnalys->price = $price;
        $patieonAnalys->save();
        return true;
    }
}
