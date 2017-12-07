<?php

namespace Denmasyarikin\Sales\Order\Requests;

class UpdateCustomerRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'exists:sales_customers,id',
            'type' => 'required|in:public,agent,company',
            'name' => 'required|min:2|max:20',
            'address' => '',
            'telephone' => 'numeric',
            'email' => 'email',
            'contact_person' => 'min:2|max:20'
        ];
    }
}
