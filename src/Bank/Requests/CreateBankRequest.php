<?php

namespace Denmasyarikin\Sales\Bank\Requests;

use App\Http\Requests\FormRequest;

class CreateBankRequest extends FormRequest
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
            'logo' => 'nullable',
            'account_number' => 'required|numeric',
            'account_name' => 'required|min:4|max:20',
        ];
    }
}
