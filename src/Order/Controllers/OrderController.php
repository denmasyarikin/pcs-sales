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
use Denmasyarikin\Sales\Order\Requests\CancelOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateStatusOrderRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderDetailTransformer;

class OrderController extends Controller
{
    use OrderRestrictionTrait;

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
     * get order list.
     *
     * @param Request $request
     * @param string  $status
     *
     * @return paginator
     */
    protected function getOrderList(Request $request, $status)
    {
        $orders = Order::whereStatus($status);

        switch ($status) {
            case 'processing':
                $orders->orderBy('start_process_date', 'DESC');
                break;
            case 'finished':
                $orders->orderBy('end_process_date', 'DESC');
                break;
            case 'closed':
                $orders->orderBy('close_date', 'DESC');
                break;
            case 'canceled':
                $orders->orderBy('updated_at', 'DESC');
                // no break
            case 'created':
            case 'draft':
                $orders->orderBy('created_at', 'DESC');
                break;
        }

        if ($request->has('key')) {
            $orders->where('id', $request->key);
        }

        $this->dateRange($orders, $request);

        return $orders->paginate($request->get('per_page') ?: 10);
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
     * create order.
     *
     * @param CreateOrderRequest $request
     *
     * @return json
     */
    public function createOrder(CreateOrderRequest $request)
    {
        $user = $request->user();

        $order = Order::create([
            'cs_user_id' => $user->id,
            'cs_name' => $user->name,
        ]);

        return new JsonResponse([
            'message' => 'Order has been created',
            'data' => ['id' => $order->id],
        ], 201);
    }

    /**
     * update order.
     *
     * @param UpdateOrderRequest $request
     *
     * @return json
     */
    public function updateOrder(UpdateOrderRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);

        $order->update($request->only(['note', 'due_date']));

        return new JsonResponse(['message' => 'Order has been updated']);
    }

    /**
     * update status order.
     *
     * @param UpdateStatusOrderRequest $request
     *
     * @return json
     */
    public function updateStatusOrder(UpdateStatusOrderRequest $request)
    {
        $order = $request->getOrder();
        $this->updateOrderStatusRetriction($order, $request->status);

        switch ($request->status) {
            case 'created':
                $order->update([
                    'remaining' => $order->total,
                    'status' => 'created',
                ]);

                if ($customer = $order->customer->customer) {
                    $customer->update(['last_order' => date('Y-m-d H:i:s')]);
                }

                $order->histories()->create(['type' => 'order', 'label' => 'created']);
                break;
            case 'processing':
                $order->update([
                    'start_process_date' => date('Y-m-d H:i:s'),
                    'status' => 'processing',
                ]);
                $order->histories()->create(['type' => 'order', 'label' => 'processing']);
                break;

            case 'finished':
                $order->update([
                    'end_process_date' => date('Y-m-d H:i:s'),
                    'status' => 'finished',
                ]);
                $order->histories()->create(['type' => 'order', 'label' => 'finished']);
                break;

            case 'closed':
                $order->update([
                    'close_date' => date('Y-m-d H:i:s'),
                    'status' => 'closed',
                ]);
                $order->histories()->create(['type' => 'order', 'label' => 'closed']);
                break;
        }

        return new JsonResponse([
            'message' => 'Order status has been updated to '.$request->status,
        ]);
    }

    /**
     * delete order.
     *
     * @param DeleteOrderRequest $request
     *
     * @return json
     */
    public function deleteOrder(DeleteOrderRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);

        $order->delete();

        return new JsonResponse(['message' => 'Order has been deleted']);
    }

    /**
     * cancel order.
     *
     * @param CancelOrderRequest $request
     *
     * @return json
     */
    public function cancelOrder(CancelOrderRequest $request)
    {
        $order = $request->getOrder();
        $this->cancelableOrder($order);

        $order->cancelation()->create($request->only(['reason', 'description']));
        $order->update(['status' => 'canceled']);

        return new JsonResponse(['message' => 'Order has been canceled']);
    }
}
