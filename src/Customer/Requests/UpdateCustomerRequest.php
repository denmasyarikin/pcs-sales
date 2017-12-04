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
            'type' => 'required|in:public,agent,company',
            'name' => 'required|max:50',
            'address' => '',
            'telephone' => 'min:4|max:20|numeric',
            'email' => 'email',
            'contact_person' => '',
            'user_id' => 'numeric|exists:core_users,id',
        ];
    }
}
