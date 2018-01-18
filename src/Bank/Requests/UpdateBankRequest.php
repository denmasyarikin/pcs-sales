<?php

namespace Denmasyarikin\Sales\Bank\Requests;

class UpdateBankRequest extends DetailBankRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'logo' => '',
            'account_number' => 'required|numeric',
            'account_name' => 'required|min:4|max:20',
        ];
    }
}
