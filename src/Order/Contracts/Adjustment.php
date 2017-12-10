<?php

namespace Denmasyarikin\Sales\Order\Contracts;

interface Adjustment
{
    /**
     * get adjustmentable.
     *
     * @return Adjustmentable
     */
    public function getAdjustmentable();
}
