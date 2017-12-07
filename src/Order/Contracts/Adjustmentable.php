<?php 

namespace Denmasyarikin\Sales\Order\Contracts;

interface Adjustmentable
{
	/**
	 * get total filed name
	 *
	 * @return string
	 */
	public function getTotalFieldName();

	/**
	 * Get the adjustments record associated with the Adjustment.
	 */
	public function adjustments();

	/**
	 * get adjustments
	 *
	 * @return Collection
	 */
	public function getAdjustments();

	/**
	 * update order total
	 *
	 * @return void
	 */
	public function updateTotal();
}
