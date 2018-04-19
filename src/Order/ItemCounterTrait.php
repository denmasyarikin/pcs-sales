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
     * Get ItemProduct.
     */
    public function getItemProductAttribute()
    {
        return $this->getItems()
                    ->where('type', 'product')
                    ->where('type_as', 'product');
    }

    /**
     * Get ItemProductCount.
     */
    public function getItemProductCountAttribute()
    {
        return $this->itemProduct->count();
    }

    /**
     * Get ItemProductProcess.
     */
    public function getItemProductProcessAttribute()
    {
        return $this->getItems()
                    ->where('type', 'product')
                    ->where('type_as', '<>', 'product');
    }

    /**
     * Get ItemProductProcessCount.
     */
    public function getItemProductProcessCountAttribute()
    {
        return $this->itemProductProcess->count();
    }

    /**
     * Get ItemService.
     */
    public function getItemServiceAttribute()
    {
        return $this->getItems()
                    ->where('type', 'service')
                    ->where('type_as', 'service');
    }

    /**
     * Get ItemServiceCount.
     */
    public function getItemServiceCountAttribute()
    {
        return $this->itemService->count();
    }

    /**
     * Get ItemGood.
     */
    public function getItemGoodAttribute()
    {
        return $this->getItems()
                    ->where('type', 'good')
                    ->where('type_as', 'good');
    }

    /**
     * Get ItemGoodCount.
     */
    public function getItemGoodCountAttribute()
    {
        return $this->itemGood->count();
    }

    /**
     * Get ItemManual.
     */
    public function getItemManualAttribute()
    {
        return $this->getItems()
                    ->where('type', 'manual')
                    ->whereIn('type_as', ['good', 'service']);
    }

    /**
     * Get ItemManualCount.
     */
    public function getItemManualCountAttribute()
    {
        return $this->itemManual->count();
    }

    /**
     * Get OverDueDate.
     *
     * @param  string  $value
     * @return string
     */
    public function getOverDueDateAttribute()
    {
        return (bool) $this->paid === false && strtotime($this->due_date) < time();
    }

    /**
     * Get OverEstimate.
     *
     * @param  string  $value
     * @return string
     */
    public function getOverEstimateAttribute()
    {
        return strtotime($this->estimated_finish_date) < time() && in_array($this->status, ['draft', 'created', 'processing']);
    }
}
