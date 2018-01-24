<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinBalance implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $wallet = request()->user()->wallet;

        return $wallet->balance >= $value && $value >= 10;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is minor to what you have in your wallet';
    }
}
