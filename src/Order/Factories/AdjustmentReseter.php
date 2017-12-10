<?php 

namespace Denmasyarikin\Sales\Order\Factories;

class AdjustmentReseter extends AdjustmentFactory
{
	/**
	 * reset adjustments
	 *
	 * @return void
	 */
	public function reset()
	{
		$this->resetAllAdjustments();
	}
}
