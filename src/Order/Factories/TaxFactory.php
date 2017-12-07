<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Taxable;

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

        if (is_null($tax)) {
            $tax = $this->createTax($this->taxable, $apply);
        } else {
            $this->taxable->adjustment_total += $tax->adjustment_total;
            $this->updateTax($tax, $apply);
        }

        $this->taxable->adjustment_total -= $tax->adjustment_total;
        $this->taxable->save();
        $this->taxable->updateTotal();
    }

    /**
     * create tax
     *
     * @param Taxable $taxable
     * @param bool $apply
     * @return OrderAdjustment
     */
    protected function createTax(Taxable $taxable, bool $apply)
    {
        return $taxable->adjustments()->create(
            $this->generateTax($percent, $taxable)
        );
    }

    /**
     * update tax
     *
     * @param Adjustment $adjustment
     * @param bool $apply
     * @return void
     */
    protected function updateTax(Adjustment $adjustment, bool $apply)
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
        $tax = ceil(($percent * $taxable->{$field}) / 100);

        return [
            'type' => 'tax',
            $field => $taxable->{$field},
            'adjustment_value' => $percent,
            'adjustment_total' => $tax,
            'total' => $taxable->{$field} + $tax
        ];
    }
}
