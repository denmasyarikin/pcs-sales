<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Customer\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function getCustomer(): ?Customer
    {
        $order = $this->getOrder();

        if ($this->customer) {
            return $this->customer;
        }

        if ($this->customer = Customer::whereChanelId($order->chanel_id)->find($this->customer_id)) {
            return $this->customer;
        }

        throw new NotFoundHttpException('Customer no in order chanel');
    }

    /**
     * get order.
     *
     * @return Order
     */
    public function getOrder(): ?Order
    {
        $order = parent::getOrder();

        $this->checkFreshData($order);

        return $order;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:sales_customers,id',
            'name' => 'required|min:2|max:20',
            'address' => '',
            'telephone' => 'numeric',
            'email' => 'email',
            'contact_person' => 'min:2|max:20',
        ];
    }
}
