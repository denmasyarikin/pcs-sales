<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Taxable;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class TaxFactory extends AdjustmentFactory
{
    /**
     * priority
     *
     * @var int
     */
    protected $priority = 4;

    /**
     * adjustment type
     *
     * @var string
     */
    protected $adjustmentType = 'markup';

    /**
     * get Adjustment
     *
     * @param Adjustmentable $adjustmentable
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
     * get Adjustment total
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed $value
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value)
    {
        return ceil(($value * $adjustmentable->total) / 100);
    }
}
