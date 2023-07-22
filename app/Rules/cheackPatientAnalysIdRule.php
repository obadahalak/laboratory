<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class cheackPatientAnalysIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected  $patient;

    public function __construct($p)
    {
        $this->patient = $p;
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

        $patieint =  auth('lab')->user()->patients->where('id', $this->patient)->first();

        $analys = $patieint->patieonAnalys->where('id', $value);

        if (count($analys)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The  :attribute  is not valied . ";
    }
}
