<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderController extends Controller
{
    /**
     * get list draft.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListDraft(Request $request)
    {
        return $this->getList($request, 'draft');
    }

    /**
     * get list created.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListCreated(Request $request)
    {
        return $this->getList($request, 'created');
    }

    /**
     * get list processing.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListProcessing(Request $request)
    {
        return $this->getList($request, 'processing');
    }

    /**
     * get list finished.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListFinished(Request $request)
    {
        return $this->getList($request, 'finished');
    }

    /**
     * get list artchived.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListArchived(Request $request)
    {
        return $this->getList($request, 'artchived');
    }

    /**
     * get list canceled.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListCanceled(Request $request)
    {
        return $this->getList($request, 'canceled');
    }

    /**
     * get list.
     *
     * @param Request $request
     * @param string  $status
     *
     * @return json
     */
    protected function getList(Request $request, $status)
    {
        $orders = $this->getOrderList($request, $status);

        $transform = new OrderListTransformer($orders);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get product list.
     *
     * @param Request $request
     * @param string  $status
     *
     * @return paginator
     */
    protected function getOrderList(Request $request, $status)
    {
        $products = Order::whereStatus($status);

        if ($request->has('key')) {
            $products->where('id', $request->key);
        }

        return $products->paginate($request->get('per_page') ?: 10);
    }

    /**
     * detail order.
     *
     * @param DetailOrderRequest $request
     *
     * @return json
     */
    public function getDetail(DetailOrderRequest $request)
    {
        $order = $request->getOrder();
        $transform = new OrderDetailTransformer($order);

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create order
     *
     * @param CreateOrderRequest $request
     * @return json
     */
    public function createOrder(CreateOrderRequest $request)
    {
        $user = $request->user();
        
        $order = Order::create([
            'cs_user_id' => $user->id,
            'cs_name' => $user->name
        ]);

        return new JsonResponse([
            'message' => 'Order has been created',
            'data' => [
                'id' => $order->id
            ]
        ], 201);
    }

    /**
     * update order
     *
     * @param UpdateOrderRequest $request
     * @return json
     */
    public function updateOrder(UpdateOrderRequest $request)
    {
        $order = $request->getOrder();

        if ($request->has('status')
            AND $request->status !== 'draft'
            AND count($order->items) === 0) {
            throw new BadRequestHttpException(
                "Can not update status to {$request->status} while item count is 0"
            );
        }

        $order->update($request->only([
            'note', 'due_date', 'start_process_date',
            'end_process_date', 'close_date', 'status'
        ]));

        return new JsonResponse(['message' => 'Order has been updated']);
    }

    /**
     * delete order
     *
     * @param DeleteOrderRequest $request
     * @return json
     */
    public function deleteOrder(DeleteOrderRequest $request)
    {
        $order = $request->getOrder();

        if (! in_array($order->status, ['draft', 'created'])) {
            throw new BadRequestHttpException('Can not delete order while status ' . $order->status);
        }

        $order->delete();

        return new JsonResponse(['message' => 'Order has been deleted']);
    }
}
