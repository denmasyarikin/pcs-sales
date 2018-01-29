<?php

namespace Denmasyarikin\Sales\Customer\Requests;

class UpdateCustomerRequest extends DetailCustomerRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chanel_id' => 'required|exists:core_chanels,id',
            'name' => 'required|max:50',
            'address' => 'required',
            'telephone' => 'nullable|digits_between:4,20|numeric',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|required_if:type,company|min:3|max:50',
            'user_id' => 'nullable|numeric|exists:core_users,id',
        ];
    }
}
