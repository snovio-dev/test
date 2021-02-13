<?php

namespace App\Http\Requests;

use App\Rules\NotBannedDomain;

class RegisterUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'email' => ['required', 'email', 'unique:users', 'max:100', new NotBannedDomain],
            'password' => ['required', 'string', 'min:5', 'max:100'],
        ];
    }


}
