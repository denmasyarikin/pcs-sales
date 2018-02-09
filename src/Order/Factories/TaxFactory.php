<?php

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Money;
use Denmasyarikin\Sales\Order\Contracts\Taxable;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class TaxFactory extends AdjustmentFactory
{
    /**
     * priority.
     *
     * @var int
     */
    protected $priority = 4;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType = 'tax';

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
     * @return string
     */
    protected function getAdjustment(Adjustmentable $adjustmentable)
    {
        if ($adjustmentable instanceof Taxable) {
            return $adjustmentable->getTax();
        }

        throw new InvalidArgumentException('Invalid adjustment type');
    }

    /**
     * get Adjustment total.
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed          $value
     *
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value)
    {
        return Money::round(($value * $adjustmentable->total) / 100);
    }

    /**
     * should be deleted
     *
     * @param Adjustment $adjustment
     * @return bool
     */
    protected function shouldBeDeleted(Adjustment $adjustment)
    {
        return $adjustment->adjustment_value == 0;
    }
}
