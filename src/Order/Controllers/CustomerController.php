<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderCustomerTransformer;

class CustomerController extends Controller
{
    /**
     * get detail
     *
     * @param DetailOrderRequest $request
     * @return json
     */
    public function getDetail(DetailOrderRequest $request)
    {
        $order = $request->getOrder();

        return new JsonResponse([
            'data' => (new OrderCustomerTransformer($order->customer))->toArray()
        ]);
    }
}
