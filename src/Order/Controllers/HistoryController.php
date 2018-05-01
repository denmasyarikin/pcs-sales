<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderHistoryRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderHistoryRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderHistoryRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderHistoryListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderHistoryDetailTransformer;

class HistoryController extends Controller
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
        $order = $request->getOrder();

        return new JsonResponse([
            'data' => (new OrderHistoryListTransformer($order->histories))->toArray(),
        ]);
    }

    /**
     * create history.
     *
     * @param CreateOrderHistoryRequest $request
     *
     * @return json
     */
    public function createHistory(CreateOrderHistoryRequest $request)
    {
        $order = $request->getOrder();
        $history = $order->histories()->create(
            $request->only(['type', 'label']) + [
                'data' => !is_null($request->data) ? json_encode($request->data) : null,
            ]
        );

        return new JsonResponse([
            'messaage' => 'Order history has been created',
            'data' => (new OrderHistoryDetailTransformer($history))->toArray(),
        ], 201);
    }

    /**
     * update history.
     *
     * @param UpdateOrderHistoryRequest $request
     *
     * @return json
     */
    public function updateHistory(UpdateOrderHistoryRequest $request)
    {
        $history = $request->getOrderHistory();

        $history->update(
            $request->only(['type', 'label']) + [
                'data' => !is_null($request->data) ? json_encode($request->data) : null,
            ]
        );

        return new JsonResponse([
            'messaage' => 'Order history has been updated',
            'data' => (new OrderHistoryDetailTransformer($history))->toArray(),
        ]);
    }

    /**
     * delete history.
     *
     * @param DeleteOrderHistoryRequest $request
     *
     * @return json
     */
    public function deleteHistory(DeleteOrderHistoryRequest $request)
    {
        $history = $request->getOrderHistory();
        $history->delete();

        return new JsonResponse(['messaage' => 'Order history has been deleted']);
    }
}
