<?php

namespace Denmasyarikin\Sales\Order\Contracts;

interface Markupable extends Adjustmentable
{
    /**
     * Get the discount record associated with the Markupable.
     */
    public function getMarkup();
}
