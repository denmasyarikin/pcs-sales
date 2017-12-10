<?php 

namespace Denmasyarikin\Sales\Order\Contracts;

interface Discountable extends Adjustmentable
{
	/**
	 * Get the discount record associated with the Discountable.
	 */
	public function getDiscount();
}
