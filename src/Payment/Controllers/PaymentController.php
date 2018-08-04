<?php

namespace Denmasyarikin\Sales\Payment\Controllers;

use Modules\User\User;
use Modules\Chanel\Chanel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Illuminate\Support\Facades\Auth;
use Denmasyarikin\Sales\Payment\Payment;
use Denmasyarikin\Sales\Payment\Factory;
use Denmasyarikin\Sales\Payment\Requests\DetailPaymentRequest;
use Denmasyarikin\Sales\Payment\Requests\CreatePaymentRequest;
use Denmasyarikin\Sales\Payment\Requests\UpdatePaymentRequest;
use Denmasyarikin\Sales\Payment\Requests\DeletePaymentRequest;
use Denmasyarikin\Sales\Payment\Transformers\PaymentListTransformer;
use Denmasyarikin\Sales\Payment\Transformers\PaymentDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentController extends Controller
{
    /**
     * get counter.
     *
     * @param Request $request
     *
     * @return Json
     */
    public function getCounter(Request $request)
    {
        $query = Payment::orderBy('created_at', 'DESC');

        $this->dateRange($query, $request);

        if ($request->has('chanel_id')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('chanel_id', $request->chanel_id);
            });
        }

        $query->whereHas('order', function ($q) use ($request) {
            if ($request->has('workspace_id')) {
                $q->where('workspace_id', $request->workspace_id);
            } else {
                $q->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
            }
        });

        $payments = $query->get();

        return new JsonResponse([
            'data' => [
                'count' => [
                    'cash' => $payments->where('payment_method', 'cash')->count(),
                    'transfer' => $payments->where('payment_method', 'transfer')->count(),
                    'total' => $payments->count(),
                ],
                'payment' => [
                    'cash' => $payments->where('payment_method', 'cash')->sum('pay'),
                    'transfer' => $payments->where('payment_method', 'transfer')->sum('pay'),
                    'total' => $payments->sum('pay'),
                ],
            ],
        ]);
    }

    /**
     * payment list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $payments = $this->getPaymentList($request);

        $transform = new PaymentListTransformer($payments);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get payment list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getPaymentList(Request $request)
    {
        $payments = Payment::whereHas('order', function ($query) {
            $query->whereNotIn('status', ['draft', 'canceled']);
        })->with('customer')->orderBy('created_at', 'DESC');

        if ($request->has('payment_method')) {
            $payments->wherePaymentMethod($request->payment_method);
        }

        if ($request->has('type')) {
            $payments->whereType($request->type);
        }

        if ($request->has('created_at')) {
            $payments->whereDate('created_at', $request->created_at);
        }

        if ('true' === $request->input('me')) {
            $payments->where('cs_user_id', Auth::user()->id);
        }

        if ($request->has('cs_user_id')) {
            $payments->where('cs_user_id', $request->cs_user_id);
        }

        if ($request->has('chanel_id')) {
            $payments->with('order', function ($q) use ($request) {
                $q->where('chanel_id', $request->chanel_id);
            });
        }

        if ($request->has('pay_debt')) {
            $payments->whereHas('order', function ($q) use ($request) {
                if ($request->pay_debt === 'true') {
                    $q->whereRaw('CAST(sales_orders.created_at AS DATE) <> CAST(sales_payments.created_at AS DATE)');
                } else if ($request->pay_debt === 'false') {
                    $q->whereRaw('CAST(sales_orders.created_at AS DATE) = CAST(sales_payments.created_at AS DATE)');
                }
            });
        }

        $payments->whereHas('order', function ($q) use ($request) {
            if ($request->has('workspace_id')) {
                $q->where('workspace_id', $request->workspace_id);
            } else {
                $q->whereIn('workspace_id', Auth::user()->workspaces->pluck('id'));
            }
        });

        if ($request->has('key')) {
            if (Order::isCode($request->key)) {
                $payments->whereHas('order', function ($q) use ($request) {
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
                $payments->whereHas('customer', function ($query) use ($request) {
                    return $query->where('name', 'like', "%{$request->key}%");
                });
                $payments->orWhere('pay', $request->key);
            }
        }

        return $payments->paginate($request->get('per_page') ?: 10);
    }

    /**
     * get detail.
     *
     * @param DetailPaymentRequest $request
     *
     * @return json
     */
    public function getDetail(DetailPaymentRequest $request)
    {
        $payment = $request->getPayment();

        return new JsonResponse([
            'data' => (new PaymentDetailTransformer($payment))->toArray(),
        ]);
    }

    /**
     * create payment.
     *
     * @param CreatePaymentRequest $request
     *
     * @return json
     */
    public function createPayment(CreatePaymentRequest $request)
    {
        $order = $this->getOrder($request->order_id);
        $factory = new Factory($order);

        if (in_array($order->status, ['canceled', 'closed'])) {
            throw new BadRequestHttpException('Order status not allowed');
        }

        if (is_null($order->customer)) {
            throw new BadRequestHttpException('Order not assign to any customer');
        }

        if ($factory->isOverpayment($request->pay)) {
            throw new BadRequestHttpException('Over payment');
        }

        $payment = $factory->pay($request->only([
            'payment_method', 'payment_slip', 'pay', 'account_id',
        ]));

        return new JsonResponse([
            'message' => 'Payment has been created',
            'data' => (new PaymentDetailTransformer($payment))->toArray(),
        ], 201);
    }

    /**
     * get order.
     *
     * @param int $orderId
     *
     * @return Order
     */
    protected function getOrder(int $orderId)
    {
        $order = Order::whereId($orderId)
                    ->where('paid', 0)
                    ->whereNotIn('status', ['canceled', 'closed'])
                    ->first();

        if ($order) {
            return $order;
        }

        throw new BadRequestHttpException('Order not found or status not allowed');
    }

    /**
     * update payment.
     *
     * @param UpdatePaymentRequest $request
     *
     * @return json
     */
    public function updatePayment(UpdatePaymentRequest $request)
    {
        $payment = $request->getPayment();
        $order = $payment->order;

        if (in_array($order->status, ['canceled', 'closed'])) {
            throw new BadRequestHttpException('Order status not allowed');
        }

        $factory = new Factory($order);

        if ($factory->isOverpayment($request->pay, $payment)) {
            throw new BadRequestHttpException('Over payment');
        }

        $payment->update($request->only([
            'payment_method', 'payment_slip', 'pay', 'account_id',
        ]));

        $factory->resetAllPayment();
        $payment = $request->getPayment(true);

        return new JsonResponse([
            'message' => 'Payment has been updated',
            'data' => (new PaymentDetailTransformer($payment))->toArray(),
        ]);
    }

    /**
     * delete payment.
     *
     * @param DeletePaymentRequest $request
     *
     * @return json
     */
    public function deletePayment(DeletePaymentRequest $request)
    {
        $payment = $request->getPayment();
        $order = $payment->order;

        if (in_array($order->status, ['canceled', 'closed'])) {
            throw new BadRequestHttpException('Order status not allowed');
        }

        $payment->delete();

        $factory = new Factory($order);
        $factory->resetAllPayment();

        return new JsonResponse(['message' => 'Payment has been deleted']);
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
        $usersIds = Payment::select('cs_user_id')
                        ->distinct('cs_user_id')
                        ->get()
                        ->pluck('cs_user_id')
                        ->toArray();

        $users = User::whereIn('id', $usersIds)->whereStatus('active')->get();

        return new JsonResponse([
            'data' => $users->toArray(),
        ]);
    }
}
