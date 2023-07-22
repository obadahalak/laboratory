<?php

namespace App\Rules;

use App\Models\Patient;
use Illuminate\Contracts\Validation\Rule;

class PatientRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function exploadIds($ids){
        $get_ids = explode(',', $ids);

        $c = count($get_ids);
        if (Patient::whereIn('id', $get_ids)->count() == $c) {
            return true;
        } else {
            return false;
        }
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
        return $this->exploadIds($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
