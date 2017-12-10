<?php

namespace Denmasyarikin\Sales\Order\Contracts;

interface Voucherable extends Adjustmentable
{
    /**
     * Get the discount record associated with the Markupable.
     */
    public function getVoucher();
}
