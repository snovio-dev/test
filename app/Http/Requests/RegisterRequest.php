<?php

namespace App\Http\Requests;

use App\BannedDomain;
use App\Rules\RegisterEmailDomainRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Request for register
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2',
            'email' => ['required', 'email', 'unique:users', new RegisterEmailDomainRule()],
            'password' => 'required|min:4|max:255',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toJson();
        Log::warning('User not register. Validation errors:' . $errors);
        throw new HttpResponseException(new Response($errors, 422));
    }
}
