<?php

namespace Denmasyarikin\Sales\Tax;

use App\Model;

class Tax extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_adjustment';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    
        static::addGlobalScope('tax', function (Builder $builder) {
            $builder->whereType('tax');
        })
    }
}
