<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Setting;
use Denmasyarikin\Sales\Order\Contracts\Taxable;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;

class TaxFactory
{
    /**
     * tax rate
     *
     * @var int
     */
    protected $taxRate;

    /**
     * taxable
     *
     * @var Taxable
     */
    protected $taxable;

    /**
     * Create a new Constructor instance.
     *
     * @param Taxable $taxable
     * @return void
     */
    public function __construct(Taxable $taxable)
    {
        $this->taxable = $taxable;
        $this->taxRate = Setting::get('system.sales.tax.tax_rate', 10);
    }

    /**
     * apply tax
     *
     * @param bool $apply
     * @return void
     */
    public function applyTax(bool $apply)
    {
        $tax = $this->taxable->getTax();
        $percent = $apply ? (float) $this->taxRate : 0;

        if (is_null($tax)) {
            $tax = $this->createTax($this->taxable, $percent);
        } else {
            $this->taxable->adjustment_total += $tax->adjustment_total;
            $this->updateTax($tax, $percent);
        }

        $this->taxable->adjustment_total -= $tax->adjustment_total;
        $this->taxable->save();
        $this->taxable->updateTotal();
    }

    /**
     * create tax
     *
     * @param Taxable $taxable
     * @param float $percent
     * @return OrderAdjustment
     */
    protected function createTax(Taxable $taxable, float $percent)
    {
        return $taxable->adjustments()->create(
            $this->generateTax($percent, $taxable)
        );
    }

    /**
     * update tax
     *
     * @param Adjustment $adjustment
     * @param float $percent
     * @return void
     */
    protected function updateTax(Adjustment $adjustment, float $percent)
    {
        return $adjustment->update(
            $this->generateTax($percent, $adjustment->getAdjustmentable())
        );
    }

    /**
     * generate tax
     *
     * @param float $percent
     * @param Taxable $taxable
     * @return array
     */
    protected function generateTax(float $percent, Taxable $taxable)
    {
        $field = $taxable->getTotalFieldName();
        $tax = ceil(($percent * $taxable->total) / 100);

        return [
            'type' => 'tax',
            $field => $taxable->total,
            'adjustment_value' => $percent,
            'adjustment_total' => $tax,
            'total' => $taxable->total + $tax
        ];
    }
}
