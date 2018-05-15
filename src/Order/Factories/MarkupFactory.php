<?php

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Money;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Markupable;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class MarkupFactory extends AdjustmentFactory
{
    /**
     * priority.
     *
     * @var int
     */
    protected $priority = 1;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType = 'markup';

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
     * @return string
     */
    protected function getAdjustment(Adjustmentable $adjustmentable)
    {
        if ($adjustmentable instanceof Markupable) {
            return $adjustmentable->getMarkup();
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
            return Money::round(($value * $adjustmentable->total) / 100);
        } else {
            return $value;
        }
    }
}
