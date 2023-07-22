<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use SebastianBergmann\Type\NullType;

class testUnitRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $id;
    public function __construct($id)
    {
        $this->id=$id;
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

        if ($this->id) {
            $data = auth('lab')->user()->testUnit->where('id', $this->id)->first();
            if ($data) {

                if ($value === $data->unit)
                    return true;
                if (auth('lab')->user()->testUnit->where('unit', $value)->count()) return false;
                return true;
            }
        } else {
            if (auth('lab')->user()->testUnit->where('unit', $value)->count()) return false;
            return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'test unit already exists in your account';
    }
}
