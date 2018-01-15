<?php

namespace Denmasyarikin\Sales\_Tax;

use Illuminate\Database\Eloquent\Builder;
use Denmasyarikin\Sales\Order\OrderAdjustment;

class Tax extends OrderAdjustment
{
    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('tax', function (Builder $builder) {
            $builder->whereHas('order', function ($query) {
                return $query->whereStatus('closed');
            })->whereType('tax');
        });
    }
}
