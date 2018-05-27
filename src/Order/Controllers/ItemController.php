<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Factories\OrderFactory;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\DetailOrderItemRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderItemRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderItemRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderItemRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderItemListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderItemDetailTransformer;

class ItemController extends Controller
{
    use OrderRestrictionTrait;

    /**
     * get list.
     *
     * @param DetailOrderRequest $request
     *
     * @return json
     */
    public function getList(DetailOrderRequest $request)
    {
        $orderItems = $request->getOrder()->getItems();

        return new JsonResponse([
            'data' => (new OrderItemListTransformer($orderItems))->toArray(),
        ]);
    }

    /**
     * detail order.
     *
     * @param DetailOrderItemRequest $request
     *
     * @return json
     */
    public function getDetail(DetailOrderItemRequest $request)
    {
        $orderItem = $request->getOrderItem();

        return new JsonResponse([
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray(),
        ]);
    }

    /**
     * create order item.
     *
     * @param CreateOrderItemRequest $request
     *
     * @return json
     */
    public function createOrderItem(CreateOrderItemRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);
        $this->restrictAdjustment($request);

        $factory = new OrderFactory($order);

        $orderItem = $factory->createOrderItem(
            $this->getDataFromRequest($request),
            $request->input('markup'),
            $request->input('markup_rule'),
            $request->input('discount'),
            $request->input('discount_rule'),
            $request->input('voucher')
        );

        return new JsonResponse([
            'message' => 'Order Item has been created',
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray(),
        ], 201);
    }

    /**
     * update order item.
     *
     * @param UpdateOrderItemRequest $request
     *
     * @return json
     */
    public function updateOrderItem(UpdateOrderItemRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);
        $this->restrictAdjustment($request);

        $orderItem = $request->getOrderItem();
        $factory = new OrderFactory($order);

        $orderItem = $factory->updateOrderItem(
            $orderItem,
            $this->getDataFromRequest($request),
            $request->input('markup'),
            $request->input('markup_rule'),
            $request->input('discount'),
            $request->input('discount_rule'),
            $request->input('voucher')
        );

        return new JsonResponse([
            'message' => 'Order Item has been updated',
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray(),
        ]);
    }

    /**
     * get data from request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function getDataFromRequest(Request $request)
    {
        return $request->only([
            'type', 'reference_id',
            'reference_type', 'reference_configurations',
            'name', 'specific',
            'quantity', 'unit_price',
            'unit_total', 'note',
            'unit_id',
        ]);
    }

    /**
     * delete order item.
     *
     * @param DeleteOrderItemRequest $request
     *
     * @return json
     */
    public function deleteOrderItem(DeleteOrderItemRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);

        $orderItem = $request->getOrderItem();
        $factory = new OrderFactory($order);

        $factory->deleteOrderItem($orderItem);

        return new JsonResponse(['message' => 'Order Item has been deleted']);
    }

    /**
     * restrict adjustment.
     *
     * @param Request $request
     */
    protected function restrictAdjustment(Request $request)
    {
        if ($request->has('discount') and intval($request->discount) > 0) {
            $this->orderAdjustmentRestriction('discount');
        }

        if ($request->has('voucher') and !empty($request->voucher)) {
            $this->orderAdjustmentRestriction('voucher');
        }
    }
}
