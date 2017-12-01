<?php

namespace Denmasyarikin\Sales\Customer\Requests;

use Denmasyarikin\Sales\Customer\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateCustomerRequest extends CreateCustomerRequest
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
    public function getCustomer(): ?Customer
    {
        if ($this->customer) {
            return $this->customer;
        }

        $id = $this->route('id');

        if ($this->customer = Customer::find($id)) {
            return $this->customer;
        }

        throw new NotFoundHttpException('Customer Not Found');
    }
}
