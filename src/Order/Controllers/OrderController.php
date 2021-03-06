<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Modules\User\User;
use Modules\Chanel\Chanel;
use Illuminate\Http\Request;
use App\Manager\Facades\Money;
use App\Manager\Facades\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Denmasyarikin\Sales\Order\Factories\TaxFactory;
use Denmasyarikin\Sales\Order\Factories\OrderFactory;
use Denmasyarikin\Sales\Order\Factories\VoucherFactory;
use Denmasyarikin\Sales\Order\Factories\DiscountFactory;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CancelOrderRequest;
use Denmasyarikin\Sales\Order\Requests\ChangeDueDateRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateStatusOrderRequest;
use Denmasyarikin\Sales\Order\Requests\ChangeEstimatedFinishDateRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderListAllTransformer;
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

        if ($request->has('workspace_id')) {
            $query->whereWorkspaceId($request->workspace_id);
        } else {
            $query->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
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
            $formated = $dt->format('Y-m-d');
            $query = Order::whereDate('created_at', $formated);

            if ($request->has('workspace_id')) {
                $query->whereWorkspaceId($request->workspace_id);
            } else {
                $query->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
            }

            $dates[] = [
                'date' => $formated,
                'data' => $this->generateCounter($query->get()),
            ];
        }

        return new JsonResponse(['data' => (array) $dates]);
    }

    /**
     * generate counter.
     *
     * @param Collection $data
     *
     * @return array
     */
    protected function generateCounter(Collection $data)
    {
        return [
            'total' => $data->count(),
            'draft' => $data->where('status', 'draft')->count(),
            'created' => $data->where('status', 'created')->count(),
            'new' => $data->whereIn('status', ['draft', 'created'])->count(),
            'processing' => $data->where('status', 'processing')->count(),
            'finished' => $data->where('status', 'finished')->count(),
            'taken' => $data->where('status', 'taken')->count(),
            'closed' => $data->where('status', 'closed')->count(),
            'canceled' => $data->where('status', 'canceled')->count(),
            'paid' => $data->whereStrict('paid', 1)->count(),
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
        $queryDueDate = Order::overDueDate($request->date);
        $queryEstimate = Order::overEstimated($request->date);

        if ($request->has('workspace_id')) {
            $queryDueDate->whereWorkspaceId($request->workspace_id);
            $queryEstimate->whereWorkspaceId($request->workspace_id);
        } else {
            $queryDueDate->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
            $queryEstimate->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
        }

        $transformDueDate = new OrderListAllTransformer($queryDueDate->get());
        $transformEstimate = new OrderListAllTransformer($queryEstimate->get());

        return new JsonResponse([
            'data' => [
                'due_date_count' => $queryDueDate->count(),
                'due_date_data' => $transformDueDate->toArray(),
                'estimated_count' => $queryEstimate->count(),
                'estimated_data' => $transformEstimate->toArray(),
            ],
        ]);
    }

    /**
     * get debt.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function getDebt(Request $request)
    {
        $query = Order::where('paid', 0);

        if ($request->has('workspace_id')) {
            $query->whereWorkspaceId($request->workspace_id);
        } else {
            $query->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
        }

        $transform = new OrderListAllTransformer($query->get());

        return new JsonResponse([
            'data' => [
                'count' => $query->count(),
                'total' => $query->sum('remaining'),
                'data' => $transform->toArray(),
            ],
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
     * get list taken.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getListTaken(Request $request)
    {
        return $this->getList($request, 'taken');
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
            $orders = Order::where('status', '<>', 'draft')
                ->orderBy('created_at', 'DESC');
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

        if ($request->has('workspace_id')) {
            $orders->whereWorkspaceId($request->workspace_id);
        } else {
            $orders->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
        }

        if ($request->has('created_at')) {
            $orders->whereDate('created_at', $request->created_at);
        }

        if ('true' === $request->input('me')) {
            $orders->where('cs_user_id', Auth::user()->id);
        }

        if ($request->has('cs_user_id')) {
            $orders->where('cs_user_id', $request->cs_user_id);
        }

        if ($request->has('key')) {
            $orders->where(function($ordQuery) use ($request) {
                $ordQuery->where('note', 'like', '%'.$request->key.'%');

                if (Order::isCode($request->key)) {
                    $ordQuery->orWhere(function ($q) use ($request) {
                        $ids = Order::getIdFromCode($request->key);
                        $q->where('id', $ids['id']);
                        $q->where('cs_user_id', $ids['cs_user_id']);
                        $q->whereHas('chanel', function ($chanel) use ($ids) {
                            $chanelIds = Chanel::getIdFromCode($ids['chanel_code']);
                            $chanel->whereType($chanelIds['type']);
                            $chanel->whereId($chanelIds['id']);
                        });
                    });
                } else {
                    $ordQuery->orWhere(function ($query) use ($request) {
                        $query->where('id', $request->key);
                        $query->orWhereHas('customer', function ($query2) use ($request) {
                            $query2->where('name', 'like', "%{$request->key}%");
                            $query2->orWhere('email', 'like', "%{$request->key}%");
                            $query2->orWhere('telephone', 'like', "%{$request->key}%");
                            $query2->orWhere('address', 'like', "%{$request->key}%");
                        });
                    });
                }
            });
        }

        if ($request->has('paid')) {
            $orders->wherePaid((bool)$request->paid);
            $orders->whereNotIn('status', ['draft', 'closed', 'canceled']);
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
        try {
            DB::beginTransaction();

            $user = $request->user();
            $order = Order::create([
                'workspace_id' => $request->workspace_id,
                'chanel_id' => $request->chanel_id,
                'cs_user_id' => $user->id,
                'cs_name' => $user->name,
            ]);

            if ($request->has('items')) {
                $order = $this->createOrderItems($order, $request->items);
            }

            if ($request->has('voucher') && !empty($request->voucher)) {
                $order = $this->applyVoucher($order, $request->voucher);
            }

            if ($request->has('voucher') && !empty($request->discount)) {
                dd(empty($request->discount), $request->discount);
                $discount = $request->discount;
                $order = $this->applyDiscount($order, intval($discount['value']), $discount['rule']);
            }

            if ($request->has('voucher') && !empty($request->ppn)) {
                $order = $this->applyTax($order, (bool) $request->ppn);
            }

            $order->histories()->create([
                'type' => 'order',
                'label' => 'draft',
                'data' => [
                    'chanel' => $order->chanel->name,
                    'workspace' => $order->workspace->name,
                ],
            ]);

            DB::commit();

            return new JsonResponse([
                'message' => 'Order has been created',
                'data' => (new OrderDetailTransformer($order))->toArray(),
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return new JsonResponse(['message' => 'error create order or items', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * create order items
     *
     * @param Order $order
     * @param array $items
     * @return Order
     */
    protected function createOrderItems(Order $order, array $items)
    {
        foreach ($items as $item) {
            if (isset($item['discount']) and intval($item['discount']) > 0) {
                $this->orderAdjustmentRestriction('discount');
            }

            if (isset($item['voucher']) and !empty($item['voucher'])) {
                $this->orderAdjustmentRestriction('voucher');
            }

            $factory = new OrderFactory($order);

            $item = $factory->createOrderItem(
                $this->getDataFromRequest($item),
                isset($item['markup']) ? $item['markup'] : null,
                isset($item['markup_rule']) ? $item['markup_rule'] : null,
                isset($item['discount']) ? $item['discount'] : null,
                isset($item['discount_rule']) ? $item['discount_rule'] : null,
                isset($item['voucher']) ? $item['voucher'] : null
            );

            $order = $item->order()->first();
        }

        return $order;
    }

    /**
     * get data from request
     *
     * @param array $item
     * @return array
     */
    protected function getDataFromRequest(array $item)
    {
        $pick = [
            'type', 'reference_id',
            'reference_type', 'reference_configurations',
            'name', 'specific',
            'quantity', 'unit_price',
            'unit_total', 'note',
            'unit_id',
        ];

        return array_intersect_key($item, array_flip($pick));
    }

    /**
     * apply tax
     *
     * @param Order $order
     * @param bool $apply
     * @return Order
     */
    protected function applyTax(Order $order, $apply)
    {
        $taxRate = Setting::get('system.sales.order.tax.tax_rate', 10);

        $factory = new TaxFactory($order);

        return $factory->apply($apply ? $taxRate : 0);
    }

    /**
     * apply discount
     *
     * @param Order $order
     * @param int $value
     * @param string $rule
     * @return Order
     */
    protected function applyDiscount(Order $order, int $value, $rule)
    {
        $factory = new DiscountFactory($order);

        return $factory->apply($value, $rule);
    }

    /**
     * apply voucher
     *
     * @param Order $order
     * @param string $code
     * @return Order
     */
    protected function applyVoucher(Order $order, $code)
    {
        $factory = new VoucherFactory($order);

        return $factory->apply($code);
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
                    'data' => [
                        'item_count' => count($order->getItems()),
                        'item_total' => Money::format($order->item_total),
                        'due_date' => $order->due_date,
                        'estimated_date' => $order->estimated_finish_date,
                    ],
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

            case 'taken':
                $order->update([
                    'taken_date' => date('Y-m-d H:i:s'),
                    'status' => 'taken',
                ]);
                $order->histories()->create(['type' => 'order', 'label' => 'taken']);
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
            'data' => [
                'reason' => $request->reason,
                'description' => $request->description,
            ],
        ]);

        return new JsonResponse(['message' => 'Order has been canceled']);
    }

    /**
     * get customer services.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getCustomerServices(Request $request)
    {
        $usersIds = Order::select('cs_user_id')
                        ->distinct('cs_user_id')
                        ->get()
                        ->pluck('cs_user_id')
                        ->toArray();

        $users = User::whereIn('id', $usersIds)->whereStatus('active')->get();

        return new JsonResponse([
            'data' => $users->toArray(),
        ]);
    }

    /**
     * change due date.
     *
     * @param ChangeDueDateRequest $request
     *
     * @return Json
     */
    public function changeDueDate(ChangeDueDateRequest $request)
    {
        $order = $request->getOrder();
        $from = $order->due_date;

        $order->update([
            'due_date' => $request->to,
        ]);

        $order->histories()->create([
            'type' => 'order',
            'label' => 'change_due_date',
            'data' => [
                'from' => $from,
                'to' => $order->due_date,
            ],
        ]);

        return new JsonResponse([
            'message' => 'Order due date has been change',
        ]);
    }

    /**
     * change estimated finish date.
     *
     * @param ChangeEstimatedFinishDateRequest $request
     *
     * @return Json
     */
    public function changeEstimatedFinishDate(ChangeEstimatedFinishDateRequest $request)
    {
        $order = $request->getOrder();
        $from = $order->estimated_finish_date;

        $order->update([
            'estimated_finish_date' => $request->to,
        ]);

        $order->histories()->create([
            'type' => 'order',
            'label' => 'change_estimated_finish_date',
            'data' => [
                'from' => $from,
                'to' => $order->estimated_finish_date,
            ],
        ]);

        return new JsonResponse([
            'message' => 'Order estimated finish date has been change',
        ]);
    }
}
