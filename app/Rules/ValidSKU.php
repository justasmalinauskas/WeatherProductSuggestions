<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidSKU implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (bool)preg_match('/^[A-Za-z]{2,3}-\d{1,4}$/i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'SKU must be in format XXX-NNN, where X is 2 or 3 letters and N is 1-4 digits';
    }
}
