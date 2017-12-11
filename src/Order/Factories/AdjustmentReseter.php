<?php

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;

class AdjustmentReseter extends AdjustmentFactory
{
    /**
     * reset adjustments.
     */
    public function reset()
    {
        $this->resetAllAdjustments();
    }

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
     * @return string
     */
    protected function getAdjustment(Adjustmentable $adjustmentable)
    {
        // never called
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
        // never called
    }
}
