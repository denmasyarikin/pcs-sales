<?php

namespace Denmasyarikin\Sales\Order\Controllers;

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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ItemController extends Controller
{
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
            'data' => (new OrderItemListTransformer($orderItems))->toArray()
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
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray()
        ]);
    }

    /**
     * create order item
     *
     * @param CreateOrderItemRequest $request
     * @return json
     */
    public function createOrderItem(CreateOrderItemRequest $request)
    {
        $this->strictItemType($request->type, $request->type_as);
        
        $order = $request->getOrder();
        $factory = new OrderFactory($order);

        $orderItem = $factory->createOrderItem(
            $request->only([
                'type', 'type_as', 'reference_id', 'name', 'specific',
                'note', 'quantity', 'unit_price', 'unit_id'
            ]),
            $request->input('markup'),
            $request->input('discount'),
            $request->input('voucher')
        );

        $orderItem->load('adjustments');

        return new JsonResponse([
            'message' => 'Order Item has been created',
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray()            
        ], 201);
    }

    /**
     * update order item
     *
     * @param UpdateOrderItemRequest $request
     * @return json
     */
    public function updateOrderItem(UpdateOrderItemRequest $request)
    {
        $this->strictItemType($request->type, $request->type_as);
        
        $order = $request->getOrder();
        $orderItem = $request->getOrderItem();
        $factory = new OrderFactory($order);

        $orderItem = $factory->updateOrderItem($orderItem, $request->only([
            'type', 'type_as', 'reference_id', 'name', 'specific',
            'note', 'quantity', 'unit_price', 'unit_id'
        ]), $request->input('markup'), $request->input('discount'), $request->input('voucher'));

        $orderItem->load('adjustments');

        return new JsonResponse([
            'message' => 'Order Item has been updated',
            'data' => (new OrderItemDetailTransformer($orderItem))->toArray()
        ]);

    }

    /**
     * strict order item type
     *
     * @param string $type
     * @param string $typeAs
     * @return void
     */
    protected function strictItemType($type, $typeAs)
    {
        switch ($type) {
            case 'good':
                if ($typeAs !== 'good') {
                    throw new BadRequestHttpException('Type As of type good only allowed good');
                }
                break;
            case 'service':
                if ($typeAs !== 'service') {
                    throw new BadRequestHttpException('Type As of type service only allowed service');
                }
            case 'manual':
                if (! in_array($typeAs, ['good', 'service'])) {
                    throw new BadRequestHttpException('Type As of type manual only allowed good or service');
                }
                break;
        }
    }
}
