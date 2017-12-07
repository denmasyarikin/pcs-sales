<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Discountable;

class DiscountFactory
{
	/**
	 * discountable
	 *
	 * @var Discountable
	 */
	protected $discountable;

	/**
	 * Create a new Constructor instance.
	 *
	 * @param Discountable $discountable
	 * @return void
	 */
	public function __construct(Discountable $discountable)
	{
		$this->discountable = $discountable;
	}

	/**
	 * apply discount
	 *
	 * @param float $percent
	 * @return void
	 */
	public function applyDiscount(float $percent)
	{
        $discount = $this->discountable->getDiscount();

        if (is_null($discount)) {
            $discount = $this->createDiscount($this->discountable, $percent);
        } else {
            $this->discountable->adjustment_total -= $discount->adjustment_total;
            $this->updateDiscount($discount, $percent);
        }

		$this->discountable->adjustment_total += $discount->adjustment_total;
		$this->discountable->save();
		$this->discountable->updateTotal();
	}

	/**
	 * create discount
	 *
     * @param Discountable $discountable
	 * @param float $percent
	 * @return OrderAdjustment
	 */
	protected function createDiscount(Discountable $discountable, float $percent)
	{
		return $discountable->adjustments()->create(
			$this->generateDiscount($percent, $discountable)
		);
	}

	/**
	 * update discount
	 *
	 * @param Adjustment $adjustment
	 * @param float $percent
	 * @return void
	 */
	protected function updateDiscount(Adjustment $adjustment, float $percent)
	{
		return $adjustment->update(
			$this->generateDiscount($percent, $adjustment->getAdjustmentable())
		);
	}

	/**
	 * generate discount
	 *
	 * @param float $percent
     * @param Discountable $discountable
	 * @return array
	 */
	protected function generateDiscount(float $percent, Discountable $discountable)
	{
		$field = $discountable->getTotalFieldName();
		$discount = ceil(($percent * $discountable->{$field}) / 100);

		return [
            'type' => 'discount',
			$field => $discountable->{$field},
			'adjustment_value' => $percent,
			'adjustment_total' => $discount,
			'total' => $discountable->{$field} - $discount
		];
	}
}
