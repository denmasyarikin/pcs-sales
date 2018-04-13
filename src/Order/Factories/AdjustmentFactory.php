<?php

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;

abstract class AdjustmentFactory
{
    /**
     * priority.
     *
     * @var int
     */
    protected $priority;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType;

    /**
     * adjustmentable.
     *
     * @var Adjustmentable
     */
    protected $adjustmentable;

    /**
     * Constructor.
     *
     * @param Adjustmentable $adjustmentable
     */
    public function __construct(Adjustmentable $adjustmentable)
    {
        $this->adjustmentable = $adjustmentable;
    }

    /**
     * apply.
     *
     * @param mixed $value
     * @param mxied $option
     *
     * @return Adjustmentable
     */
    public function apply($value, $option = null)
    {
        $adjustment = $this->getAdjustment($this->adjustmentable);

        if (is_null($adjustment)) {
            $adjustment = $this->createAdjustment($value, $option);
        } else {
            $this->substractAdjustmentable($adjustment);
            $adjustment = $this->updateAdjustment($adjustment, $value, $option);
        }

        $this->updateAdjustmentable($adjustment);

        if ($this->isNeedResetAdjustment()) {
            $this->resetAllAdjustments();
        }

        // if value has been applyed and value not effected just delete
        if ($this->shouldDelete($adjustment, $option)) {
            $adjustment->delete();
        }

        return $this->adjustmentable;
    }

    /**
     * check is need reset adjustment.
     *
     * @return bool
     */
    protected function isNeedResetAdjustment()
    {
        return $this->adjustmentable->adjustments()->count() > 1;
    }

    /**
     * substract adjustmentable.
     *
     * @param Adjustment $adjustment
     */
    protected function substractAdjustmentable(Adjustment $adjustment)
    {
        $this->adjustmentable->adjustment_total -= $adjustment->adjustment_total;
        $this->adjustmentable->total -= $adjustment->adjustment_total;
    }

    /**
     * update adjustmentable.
     *
     * @param Adjustment $adjustment
     */
    protected function updateAdjustmentable(Adjustment &$adjustment)
    {
        $this->adjustmentable->adjustment_total += $adjustment->adjustment_total;
        $this->adjustmentable->save();

        // if adjustment_total or (item_total or unit_total) updated
        // update adjustmentable total value
        $this->adjustmentable->updateTotal();
    }

    /**
     * reset all adjustments by changed adjustment.
     */
    protected function resetAllAdjustments()
    {
        $this->resetAdjustmentable();
        $adjustments = $this->adjustmentable->getAdjustments();

        foreach ($adjustments as $adjustment) {
            $option = null;
            $adjustmentValue = $adjustment->adjustment_value;
            $factory = $this->createFactory($adjustment);

            switch ($adjustment->type) {
                case 'discount':
                    $option = null === $adjustment->adjustment_value ? 'amount' : 'percentage';
                    if ('amount' === $option) {
                        $adjustmentValue = $adjustment->adjustment_total * -1;
                    }
                    break;
                case 'markup':
                    $option = null === $adjustment->adjustment_value ? 'amount' : 'percentage';
                    if ('amount' === $option) {
                        $adjustmentValue = $adjustment->adjustment_total;
                    }
                    break;
            }

            $adjustment = $factory->updateAdjustment($adjustment, $adjustmentValue, $option);

            $this->updateAdjustmentable($adjustment);
        }
    }

    /**
     * reset adjustment.
     */
    protected function resetAdjustmentable()
    {
        $itemTotal = $this->adjustmentable->total - $this->adjustmentable->adjustment_total;

        $this->adjustmentable->update([
            'adjustment_total' => 0,
            'total' => $itemTotal,
        ]);
    }

    /**
     * create factory.
     *
     * @param Adjustment $adjustment
     *
     * @return Factory
     */
    protected function createFactory(Adjustment $adjustment)
    {
        switch ($adjustment->type) {
            case 'markup':
                return new MarkupFactory($this->adjustmentable);
                break;
            case 'discount':
                return new DiscountFactory($this->adjustmentable);
                break;
            case 'voucher':
                return new VoucherFactory($this->adjustmentable);
                break;
            case 'tax':
                return new TaxFactory($this->adjustmentable);
                break;
        }
    }

    /**
     * create adjustment.
     *
     * @param mixed $value
     * @param mixed $option
     *
     * @return OrderAdjustment
     */
    public function createAdjustment($value, $option = null)
    {
        return $this->adjustmentable->adjustments()->create(
            $this->generateAdjustment($value, $option)
        );
    }

    /**
     * update adjustment.
     *
     * @param Adjustment $adjustment
     * @param mixed      $value
     * @param mixed      $option
     *
     * @return Adjustment
     */
    public function updateAdjustment(Adjustment $adjustment, $value, $option = null)
    {
        $adjustment->update(
            $this->generateAdjustment($value, $option)
        );

        return $adjustment;
    }

    /**
     * generate adjustment.
     *
     * @param mixed $value
     * @param mixed $option
     *
     * @return array
     */
    protected function generateAdjustment($value, $option = null)
    {
        $adjustmentTotal = $this->getAdjustmentTotal($this->adjustmentable, $value, $option);
        $adjustmentValue = $this->getAdjustmentValue($value, $option);

        return [
            'type' => $this->adjustmentType,
            'priority' => $this->priority,
            'adjustment_origin' => $this->adjustmentable->total,
            'adjustment_total' => $adjustmentTotal,
            'adjustment_value' => $adjustmentValue,
            'total' => $this->adjustmentable->total + $adjustmentTotal,
        ];
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
        return false;
    }

    /**
     * get Adjustment.
     *
     * @param Adjustmentable $adjustmentable
     *
     * @return string
     */
    abstract protected function getAdjustment(Adjustmentable $adjustmentable);

    /**
     * get Adjustment total.
     *
     * @param Adjustmentable $adjustmentable
     * @param mixed          $value
     * @param mixed          $option
     *
     * @return int
     */
    abstract protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value, $option);

    /**
     * get Adjustment value.
     *
     * @param mixed $value
     * @param mixed $option
     *
     * @return int
     */
    abstract protected function getAdjustmentValue($value, $option);
}
