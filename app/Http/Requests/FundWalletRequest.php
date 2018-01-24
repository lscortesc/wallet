<?php

namespace App\Http\Requests;

class FundWalletRequest extends BaseRequest
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
            'amount' => 'required|numeric|min:0.1',
            'cardnumber' => [
                'required',
                'regex:/^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/u'
            ],
            'exp_date' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/'
            ],
            'cvv' => 'required'
        ];
    }
}
