<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderListTransformer;

class OrderController extends Controller
{
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
        dd($request->getOrder());
    }
}
