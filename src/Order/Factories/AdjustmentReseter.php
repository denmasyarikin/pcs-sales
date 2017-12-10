<?php

namespace Denmasyarikin\Sales\Order\Factories;

class AdjustmentReseter extends AdjustmentFactory
{
    /**
     * reset adjustments.
     */
    public function reset()
    {
        $this->resetAllAdjustments();
    }
}
