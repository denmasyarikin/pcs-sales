<?php

namespace Denmasyarikin\Sales\Payment\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
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
     * get counter
     *
     * @param Request $request
     * @return Json
     */
    public function getCounter(Request $request)
    {
        $query = Payment::orderBy('created_at', 'DESC');

        $this->dateRange($query, $request);

        $payments = $query->get();

        return new JsonResponse([
            'data' => [
                'count' => [
                    'total' => $payments->count(),
                    'transfer' =>  $payments->where('payment_method', 'transfer')->count(),
                    'cash' =>  $payments->where('payment_method', 'cash')->count()
                ],
                'payment' => [
                    'total' => $payments->sum('pay'),
                    'transfer' => $payments->where('payment_method', 'transfer')->sum('pay'),
                    'cash' => $payments->where('payment_method', 'cash')->sum('pay')
                ]
            ]
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
        $payments = Payment::whereHas('order', function($query) {
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

        if ($request->has('key')) {
            $payments->whereHas('customer', function ($query) use ($request) {
                return $query->where('name', 'like', "%{$request->key}%");
            });
            $payments->orWhere('id', $request->key);
            $payments->orWhere('pay', $request->key);
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
            'payment_method', 'payment_slip', 'pay', 'account_id'
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
            'payment_method', 'payment_slip', 'pay', 'account_id'
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
}
