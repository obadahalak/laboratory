<?php

namespace App\Rules;

use Exception;
use App\Models\Section;
use PhpParser\Node\Expr\Throw_;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AnalysRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $analys;
    public $indexNotHaveAnalysId = [];
    public function __construct($analys)
    {
        $this->analys = $analys;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function cheachIfSectionsIsValid($ids)
    {
        $countOfIDs = count($ids);
        foreach ($ids as  $section) {

            $sections = Section::find($section);
            if (count($sections) == $countOfIDs) {

                $indexNotHaveAnalysId2 = [];

                for ($i = 0; $i < count($this->analys); $i++) {
                    if (!isset($this->analys[$i]['analyz_id']) && $section->type == Section::MAINSECTION) {

                        array_push($indexNotHaveAnalysId2, $i);
                    }
                }

                if (count($indexNotHaveAnalysId2)) {

                    $message = Response()->json(["sections must be have analysID" => $indexNotHaveAnalysId2], 422);

                    throw new HttpResponseException($message, 422);
                }
                return true;
            }
            };
            return false;
    }

    public function passes($attribute, $value)
    {
        if ($this->analys != '') {

            if ($this->cheachIfSectionsIsValid($value)) {


                $indexNotHaveAnalysId2 = [];

                for ($i = 0; $i < count($this->analys); $i++) {
                    if (!isset($this->analys[$i]['analyz_id']) && $section->type == Section::MAINSECTION) {

                        array_push($indexNotHaveAnalysId2, $i);
                    }
                }

                if (count($indexNotHaveAnalysId2)) {

                    $message = Response()->json(["sections must be have analysID" => $indexNotHaveAnalysId2], 422);

                    throw new HttpResponseException($message, 422);
                }
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute  error message.';
    }
}
