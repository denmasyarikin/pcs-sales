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
     * @param mixed          $option
     *
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value, $option = null)
    {
        return Money::round(($value * $adjustmentable->total) / 100);
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
        return $value;
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
        return 0 == $adjustment->adjustment_value;
    }
}
