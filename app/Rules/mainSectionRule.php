<?php

namespace App\Rules;

use App\Models\Section;
use Illuminate\Contracts\Validation\Rule;

class mainSectionRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $ids = explode(',', $value);
        $countsOfIds = count($ids);
        $countOfData = Section::AccoutMainSection()->whereHas('analyz')->find($ids)->count();
        if ($countOfData == $countsOfIds) return true;
        return false;


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'section id  is invaled. ^_^';
    }
}
