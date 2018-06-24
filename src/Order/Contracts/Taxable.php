<?php

namespace Denmasyarikin\Sales\Order\Contracts;

interface Taxable extends Adjustmentable
{
    /**
     * Get the discount record associated with the Markupable.
     */
    public function getTax();
}
