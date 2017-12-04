<?php

namespace Denmasyarikin\Sales\Customer\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Customer\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailCustomerRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
