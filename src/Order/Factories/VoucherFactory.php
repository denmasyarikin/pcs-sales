<?php

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Voucherable;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class VoucherFactory extends AdjustmentFactory
{
    /**
     * priority.
     *
     * @var int
     */
    protected $priority = 3;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType = 'voucher';

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
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
     * get Adjustment total.
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed          $value
     * @param mixed          $option
     *
     * @return string
     */
    protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $option = null)
    {
        dd('TODO Apply voucher');
    }

    /**
     * get Adjustment value.
     *
     * @param mixed $value
     * @param mixed $option
     *
     * @return string
     */
    protected function getAdjustmentValue($value, $option = null)
    {
        return $value;
    }

    /**
     * should be deleted.
     *
     * @param Adjustment $adjustment
     * @param mixed      $option
     *
     * @return bool
     */
    protected function shouldDelete(Adjustment $adjustment, $option = null)
    {
        return '' == $adjustment->adjustment_value;
    }
}
