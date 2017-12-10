<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\JsonResponse;
use Denmasyarikin\Sales\Order\Order;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateCustomerRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderCustomerTransformer;

class CustomerController extends Controller
{
    use OrderRestrictionTrait;

    /**
     * get detail.
     *
     * @param DetailOrderRequest $request
     *
     * @return json
     */
    public function getDetail(DetailOrderRequest $request)
    {
        $order = $request->getOrder();

        return new JsonResponse([
            'data' => (new OrderCustomerTransformer($order->customer))->toArray(),
        ]);
    }

    /**
     * update or add customer.
     *
     * @param UpdateCustomerRequest $request
     *
     * @return json
     */
    public function updateCustomer(UpdateCustomerRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);

        $customer = $request->only([
            'customer_id', 'type', 'name', 'address',
            'telephone', 'email', 'contact_person',
        ]);

        if (is_null($order->customer)) {
            $orderCustomer = $this->createOrderCustomer($order, $customer);
        } else {
            $orderCustomer = $order->customer;
            $orderCustomer->update($customer);
        }

        return new JsonResponse([
            'message' => 'Order Customer has been updated',
            'data' => (new OrderCustomerTransformer($orderCustomer))->toArray(),
        ]);
    }

    /**
     * create order customer.
     *
     * @param Order $order
     * @param array $customer
     *
     * @return OrderCustomer
     */
    protected function createOrderCustomer(Order $order, array $customer)
    {
        return $order->customer()->create($customer);
    }
}
