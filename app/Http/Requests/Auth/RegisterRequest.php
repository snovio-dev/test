<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Auth\AuthValidationRules\DomainValidationRule;
use App\Http\Requests\Auth\AuthValidationRules\IpValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class OrderIndexRequest
 * @package App\Http\Requests
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'ip_address' => ['required', 'ip', new IpValidationRule, new DomainValidationRule],
        ];
    }

    /**
     * prepare data
     */
    public function prepareForValidation(): void
    {
        $this->merge(['ip_address' => $_SERVER['REMOTE_ADDR']]);
        $this->merge(['password' => bcrypt($this->input('password')) ]);
    }
}
