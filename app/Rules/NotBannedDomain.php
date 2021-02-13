<?php

namespace App\Rules;

use App\Models\BannedDomain;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class NotBannedDomain implements Rule
{
    private $emailDomain;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->emailDomain = explode('@', $value)[1];

        return BannedDomain::where('domain', $this->emailDomain)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        Log::channel('reg-error')
            ->error("`{$this->emailDomain}` had been BANNED but tried to register.");

        return 'Your email domain was banned.';
    }
}
