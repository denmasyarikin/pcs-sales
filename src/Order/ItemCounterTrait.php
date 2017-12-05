<?php

namespace Denmasyarikin\Sales\Order;

trait ItemCounterTrait
{
	/**
	 * Get ItemCount.
	 */
	public function getItemCountAttribute()
	{
		return $this->item_product_count
				+ $this->item_service_count
				+ $this->item_good_count
				+ $this->item_manual_count;
	}

	/**
	 * Get ItemProductCount.
	 */
	public function getItemProductCountAttribute()
	{
		return $this->getItems()
					->where('type', 'product')
					->where('type_as', 'product')
					->count();
	}

	/**
	 * Get ItemProductProcessCount.
	 */
	public function getItemProductProcessCountAttribute()
	{
		return $this->getItems()
					->where('type', 'product')
					->where('type_as', '<>', 'product')
					->count();
	}

	/**
	 * Get ItemServiceCount.
	 */
	public function getItemServiceCountAttribute()
	{
		return $this->getItems()
					->where('type', 'service')
					->where('type_as', 'service')
					->count();
	}

	/**
	 * Get ItemGoodCount.
	 */
	public function getItemGoodCountAttribute()
	{
		return $this->getItems()
					->where('type', 'good')
					->where('type_as', 'good')
					->count();
	}

	/**
	 * Get ItemManualCount.
	 */
	public function getItemManualCountAttribute()
	{
		return $this->getItems()
					->where('type', 'manual')
					->whereIn('type_as', ['good', 'service'])
					->count();
	}
}