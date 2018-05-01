<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Customer\Customer;

class UpdateCustomerRequest extends DetailOrderRequest
{
    /**
     * customer.
     *
     * @var Customer
     */
    public $customer;

    /**
     * get customer.
     *
     * @return Customer
     */
    public function getCustomer()
    {
        $order = $this->getOrder();

        if ($this->customer) {
            return $this->customer;
        }

        if ($this->customer = Customer::whereChanelId($order->chanel_id)->find((int) $this->customer_id)) {
            return $this->customer;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'nullable|exists:sales_customers,id',
            'name' => 'required|min:2|max:20',
            'address' => 'nullable',
            'telephone' => 'nullable|numeric',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|min:2|max:20',
        ];
    }
}
