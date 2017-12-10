<?php

namespace Denmasyarikin\Sales\Product;

trait ProcessCounterTrait
{
    /**
     * Get ProcessCount.
     */
    public function getProcessCountAttribute()
    {
        return $this->getProcesses()->count();
    }

    /**
     * Get ProcessServiceCount.
     */
    public function getProcessServiceCountAttribute()
    {
        return $this->getProcesses()->where('type', 'service')->count();
    }

    /**
     * Get ProcessGoodCount.
     */
    public function getProcessGoodCountAttribute()
    {
        return $this->getProcesses()->where('type', 'good')->count();
    }

    /**
     * Get ProcessManualCount.
     */
    public function getProcessManualCountAttribute()
    {
        return $this->getProcesses()->where('type', 'manual')->count();
    }
}
