<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use App\Manager\Facades\Setting;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\Factories\TaxFactory;
use Denmasyarikin\Sales\Order\Factories\VoucherFactory;
use Denmasyarikin\Sales\Order\Factories\DiscountFactory;
use Denmasyarikin\Sales\Order\Requests\AdjustmentTaxRequest;
use Denmasyarikin\Sales\Order\Requests\AdjustmentVoucherRequest;
use Denmasyarikin\Sales\Order\Requests\AdjustmentDiscountRequest;

class AdjustmentController extends Controller
{
    use OrderRestrictionTrait;

    /**
     * apply discount
     *
     * @param AdjustmentDiscountRequest $request
     * @return json
     */
    public function applyDiscount(AdjustmentDiscountRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);
        $this->hasItems($order);

        $factory = new DiscountFactory($order);
        $factory->apply($request->percent);

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
        $this->updateableOrder($order);
        $this->hasItems($order);

        $taxRate = Setting::get('system.sales.order.tax.tax_rate', 10);

        $factory = new TaxFactory($order);
        $factory->apply((bool) $request->apply ? $taxRate : 0);

        return new JsonResponse(['message' => 'Tax has been applyed']);
    }

    /**
     * apply discount
     *
     * @param AdjustmentVoucherRequest $request
     * @return json
     */
    public function applyVoucher(AdjustmentVoucherRequest $request)
    {
        $order = $request->getOrder();
        $this->updateableOrder($order);
        $this->hasItems($order);

        $factory = new VoucherFactory($order);
        $factory->apply($request->code);

        return new JsonResponse(['message' => 'Voucher has been applyed']);
    }
}
