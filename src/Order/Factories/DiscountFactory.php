<?php

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Money;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Discountable;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class DiscountFactory extends AdjustmentFactory
{
    /**
     * priority.
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType = 'discount';

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
     * @return string
     */
    protected function getAdjustment(Adjustmentable $adjustmentable)
    {
        if ($adjustmentable instanceof Discountable) {
            return $adjustmentable->getDiscount();
        }

        throw new InvalidArgumentException('Invalid adjustment type');
    }

    /**
     * get Adjustment total.
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed          $value
     * @param mixed          $option
     *
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value, $option = null)
    {
        if ('percentage' === $option) {
            return Money::round(($value * $adjustmentable->total) / 100) * -1;
        } else {
            return $value * -1;
        }
    }

    /**
     * get Adjustment value.
     *
     * @param mixed $value
     * @param mixed $option
     *
     * @return string
     */
    protected function getAdjustmentValue($value, $option = null)
    {
        if ('percentage' === $option) {
            return $value;
        } else {
            return null;
        }
    }

    /**
     * should be deleted.
     *
     * @param Adjustment $adjustment
     * @param mixed      $option
     *
     * @return bool
     */
    protected function shouldDelete(Adjustment $adjustment, $option = null)
    {
        if ('percentage' === $option) {
            return 0 == $adjustment->adjustment_value;
        } else { //amount
            return 0 == $adjustment->adjustment_total;
        }
    }
}
