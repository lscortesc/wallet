<?php

namespace App\Http\Requests;

use Oauth\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class BaseRequest
 * @package App\Http\Requests
 */
class BaseRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->response([
                'errors' => $validator->errors()->getMessages()
            ], 422)
        );
    }
}
