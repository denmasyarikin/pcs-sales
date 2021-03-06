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
            'address' => 'nullable',
            'telephone' => 'nullable|numeric',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|min:3|max:50',
            'user_id' => 'nullable|numeric|exists:core_users,id',
            'due_date_day_count' => 'nullable|numeric',
        ];
    }
}
