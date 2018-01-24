<?php

namespace App\Http\Requests;

use App\Rules\MinBalance;

class TransferAccountBalanceRequest extends BaseRequest
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
            'amount' => [
                'required',
                'numeric',
                new MinBalance
            ],
            'account_number' => 'required|min:10',
            'account_name' => 'required',
            'account_bank' => 'required'
        ];
    }
}
