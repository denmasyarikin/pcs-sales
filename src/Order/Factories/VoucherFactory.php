<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Voucherable;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class VoucherFactory extends AdjustmentFactory
{
    /**
     * priority
     *
     * @var int
     */
    protected $priority = 3;

    /**
     * adjustment type
     *
     * @var string
     */
    protected $adjustmentType = 'discount';

    /**
     * get Adjustment
     *
     * @param Adjustmentable $adjustmentable
     * @return string
     */
    protected function getAdjustment(Adjustmentable $adjustmentable)
    {
        if ($adjustmentable instanceof Voucherable) {
            return $adjustmentable->getVoucher();
        }

        throw new InvalidArgumentException('Invalid adjustment type');
    }

    /**
     * get Adjustment total
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed $value
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value)
    {
        dd('TODO Apply voucher');
    }
}
