<?php

namespace Denmasyarikin\Sales\Customer\Requests;

use Denmasyarikin\Sales\Customer\Customer;

class UpdateCustomerRequest extends DetailCustomerRequest
{
    /**
     * get customer.
     *
     * @return Customer
     */
    public function getCustomer(): ?Customer
    {
        $customer = parent::getCustomer();

        $this->checkFreshData($customer);

        return $customer;
    }

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
            'telephone' => 'digits_between:4,20|numeric',
            'email' => 'email',
            'contact_person' => 'required_if:type,company|min:3|max:50',
            'user_id' => 'numeric|exists:core_users,id',
        ];
    }
}
