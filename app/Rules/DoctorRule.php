<?php

namespace App\Rules;

use App\Models\Doctor;
use Illuminate\Contracts\Validation\Rule;

class DoctorRule implements Rule
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
        $doctors_id = explode(',', $ids);

        $doctors=Doctor::whereIn('id', $doctors_id);
        if($doctors->exists())return true;
        return false;
      
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
