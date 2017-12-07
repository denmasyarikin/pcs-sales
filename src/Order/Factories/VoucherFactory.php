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
     * @param string $code
     * @return void
     */
    public function applyVoucher($code)
    {
        // TODO implemnt voucher
    }
}
