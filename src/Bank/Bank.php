<?php

namespace Denmasyarikin\Sales\Bank;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_banks';
}
