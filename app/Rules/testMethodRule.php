<?php

namespace App\Rules;

use App\Models\TestMethod;
use Illuminate\Contracts\Validation\Rule;

class testMethodRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $id;
    public function __construct($id)
    {
        $this->id = $id;
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
            $data = auth('lab')->user()->testMethod->where('id', $this->id)->first();
            if ($data) {
                if ($value === $data->test_method)
                    return true;
                if (auth('lab')->user()->testMethod->where('test_method', $value)->count()) return false;
                return true;
            }
        } else {
            if (auth('lab')->user()->testMethod->where('test_method', $value)->count()) return false;
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
        return 'test method already exists in your account';
    }
}
