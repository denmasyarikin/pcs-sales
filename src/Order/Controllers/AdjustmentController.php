<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Factories\DiscountFactory;
use Denmasyarikin\Sales\Order\Requests\AdjustmentTaxRequest;
use Denmasyarikin\Sales\Order\Requests\AdjustmentVoucherRequest;
use Denmasyarikin\Sales\Order\Requests\AdjustmentDiscountRequest;

class AdjustmentController extends Controller
{
    /**
     * apply discount
     *
     * @param AdjustmentDiscountRequest $request
     * @return json
     */
    public function applyDiscount(AdjustmentDiscountRequest $request)
    {
        $order = $request->getOrder();

        $factory = new DiscountFactory($order);
        $factory->applyDiscount((float) $request->percent);

        return new JsonResponse(['message' => 'Discount has been applyed']);
    }

    /**
     * apply tax
     *
     * @param AdjustmentTaxRequest $request
     * @return json
     */
    public function applyTax(AdjustmentTaxRequest $request)
    {
        $order = $request->getOrder();

        $factory = new TaxFactory($order);
        $factory->applyTax((bool) $request->apply);

        return new JsonResponse(['message' => 'Discount has been applyed']);
    }
}
