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
     * sequence.
     *
     * @var int
     */
    protected $sequence = 2;

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
     *
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value)
    {
        if ('percentage' === $this->adjustmentRule) {
            return Money::round(($value * $adjustmentable->total) / 100) * -1;
        } else {
            return $value * -1;
        }
    }
}
