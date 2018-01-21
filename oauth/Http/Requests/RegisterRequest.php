<?php

namespace Oauth\Http\Requests;

/**
 * Class RegisterRequest
 * @package Oauth\Http\Requests
 */
class RegisterRequest extends BaseRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:70',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed:password_confirmation'
        ];
    }
}