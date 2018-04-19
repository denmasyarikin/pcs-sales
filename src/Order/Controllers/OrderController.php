<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use \DateTime;
use \DatePeriod;
use \DateInterval;
use Illuminate\Http\Request;
use App\Manager\Facades\Money;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CancelOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateStatusOrderRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderDetailTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderListDetailTransformer;

class OrderController extends Controller
{
    use OrderRestrictionTrait;

    /**
     * get counter.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function getCounter(Request $request)
    {
        $date = date('Y-m-d');
        $query = Order::orderBy('created_at', 'ASC');
        
        if ($request->has('date')) {
            $date = $request->date;
        }
        
        $data = $query->whereDate('created_at', $date)->get();
        
        return new JsonResponse(['data' => $this->generateCounter($data)]);
    }

    /**
     * get counters.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function getCounters(Request $request)
    {
        $dates = [];
        $start = new DateTime($request->start_date);
        $interval = DateInterval::createFromDateString('1 day');
        $end = (new DateTime($request->end_date))->add($interval);
        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $formated = $dt->format("Y-m-d");
            $data = Order::whereDate('created_at', $formated)->get();
            $dates[] = [
                'date' => $formated,
                'data' => $this->generateCounter($data)
            ];
        }
        
        return new JsonResponse(['data' => (array)$dates]);
    }

    /**
     * generate counter
     *
     * @param Collection $data
     * @return array
     */
    protected function generateCounter(Collection $data)
    {
        return [
            'total' => $data->count(),
            'draft' => $data->where('status', 'draft')->count(),
            'created' =>  $data->where('status', 'created')->count(),
            'new' => $data->whereIn('status', ['draft', 'created'])->count(),
            'processing' =>  $data->where('status', 'processing')->count(),
            'finished' =>  $data->where('status', 'finished')->count(),
            'closed' =>  $data->where('status', 'closed')->count(),
            'canceled' =>  $data->where('status', 'canceled')->count(),
            'paid' =>  $data->whereStrict('paid', 1)->count(),
        ];
    }

    /**
     * get overs.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function getOvers(Request $request)
    {
        $queryDueDate = Order::overDueDate($request->date)->get();
        $queryEstimate = Order::overEstimated($request->date)->get();
        
        return new JsonResponse([
            'data' => [
                'due_date_count' => $queryDueDate->count(),
                'due_date_data' => $queryDueDate->toArray(),
                'estimated_count' => $queryEstimate->count(),
                'estimated_data' => $queryEstimate->toArray()
            ]
        ]);
    }

    /**
     * get list all.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListAll(Request $request)
    {
        return $this->getList($request);
    }

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
     * get list closed.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListClosed(Request $request)
    {
        return $this->getList($request, 'closed');
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
    protected function getList(Request $request, $status = null)
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
    protected function getOrderList(Request $request, $status = null)
    {
        if (!is_null($status)) {
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
                    break;
                case 'created':
                case 'draft':
                    $orders->orderBy('created_at', 'DESC');
                    break;
            }
        } else {
            $orders = Order::orderBy('created_at', 'DESC');
        }

        if ($request->has('customer_id')) {
            $orders->where('status', '<>', 'draft');
            $orders->whereHas('customer', function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            });
        }

        if ($request->has('product_id')) {
            $orders->where('status', '<>', 'draft');
            $orders->whereHas('items', function ($query) use ($request) {
                $query->where('reference_id', $request->product_id);
                $query->where('reference_type', 'product');
            });
        }

        if ($request->has('chanel_id')) {
            $orders->whereChanelId($request->chanel_id);
        }

        if ($request->has('created_at')) {
            $orders->whereDate('created_at', $request->created_at);
        }

        if ($request->input('me') === 'true') {
            $orders->where('cs_user_id', Auth::user()->id);
        }

        if ($request->has('key')) {
            $orders->where(function ($query) use ($request) {
                $query->where('id', $request->key);
                $query->orWhereHas('customer', function ($query2) use ($request) {
                    $query2->where('name', 'like', "%{$request->key}%");
                    $query2->orWhere('email', 'like', "%{$request->key}%");
                });
            });
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
            'chanel_id' => $request->chanel_id,
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

        $this->updateableOrder($order, false);

        $order->update($request->only(['note', 'due_date', 'estimated_finish_date']));

        return new JsonResponse([
            'message' => 'Order has been updated',
            'data' => (new OrderListDetailTransformer($order))->toArray(),
        ]);
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
                $order->update(['status' => 'created']);

                if ($customer = $order->customer->customer) {
                    $customer->update(['last_order' => date('Y-m-d H:i:s')]);
                }

                $order->histories()->create([
                    'type' => 'order',
                    'label' => 'created',
                    'data' => json_encode([
                        'item_count' => count($order->getPrimaryItems()),
                        'item_total' => Money::format($order->item_total),
                    ]),
                ]);
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
        $order->histories()->create([
            'type' => 'order',
            'label' => 'canceled',
            'data' => json_encode([
                'reason' => $request->reason,
                'description' => $request->description,
            ]),
        ]);

        return new JsonResponse(['message' => 'Order has been canceled']);
    }
}
