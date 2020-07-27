<?php

namespace App\Rules;

use App\BannedDomain;
use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for email domain restriction
 */
class RegisterEmailDomainRule implements Rule
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
        $emailParts = explode('@', $value);
        if (isset($emailParts[1]) && !BannedDomain::where('domain', $emailParts[1])->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You email domain is not allow';
    }
}
