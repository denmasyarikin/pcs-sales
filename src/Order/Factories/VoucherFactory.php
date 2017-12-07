<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Voucherable;

class VoucherFactory
{
    /**
     * voucherable
     *
     * @var Voucherable
     */
    protected $voucherable;

    /**
     * Create a new Constructor instance.
     *
     * @param Voucherable $voucherable
     * @return void
     */
    public function __construct(Voucherable $voucherable)
    {
        $this->voucherable = $voucherable;
    }

    /**
     * apply markup
     *
     * @param string $voucher
     * @return void
     */
    public function applyMarkup($voucher)
    {
        // TODO implemnt voucher
    }
}
