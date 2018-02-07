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
     *
     * @return Adjustmentable
     */
    public function apply($value)
    {
        $adjustment = $this->getAdjustment($this->adjustmentable);

        if (is_null($adjustment)) {
            $adjustment = $this->createAdjustment($value);
            $this->updateAdjustmentable($adjustment);
        } elseif ($this->noNeedResetAdjustment($adjustment)) {
            $this->substractAdjustmentable($adjustment);
            $adjustment = $this->updateAdjustment($adjustment, $value);
            $this->updateAdjustmentable($adjustment);
        } else {
            $this->resetAllAdjustments($adjustment, $value);
        }

        // if value has been applyed and value not effected just delete
        if ($this->shouldBeDeleted($adjustment)) {
            $adjustment->delete();
        } 

        return $this->adjustmentable;
    }

    /**
     * check is no need reset adjustment.
     *
     * @param Adjustment $adjustment
     *
     * @return bool
     */
    protected function noNeedResetAdjustment(Adjustment $adjustment)
    {
        return $this->adjustmentable->total === $adjustment->total;
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
    protected function updateAdjustmentable(Adjustment $adjustment)
    {
        $this->adjustmentable->adjustment_total += $adjustment->adjustment_total;
        $this->adjustmentable->save();

        // if adjustment_total or (item_total or unit_total) updated
        // update adjustmentable total value
        $this->adjustmentable->updateTotal();
    }

    /**
     * reset all adjustments by changed adjustment.
     *
     * @param Adjustment $updateAdjustment
     * @param mixed      $value
     */
    protected function resetAllAdjustments(Adjustment $updateAdjustment = null, $value = null)
    {
        $this->resetAdjustmentable();
        $adjustments = $this->adjustmentable->adjustments()->orderBy('priority', 'ASC')->get();

        foreach ($adjustments as $adjustment) {
            $factory = $this->createFactory($adjustment);

            if (!is_null($updateAdjustment) and $updateAdjustment->id === $adjustment->id) {
                $adjustment = $factory->updateAdjustment($adjustment, $value);
            } else {
                $adjustment = $factory->updateAdjustment($adjustment, $adjustment->adjustment_value);
            }

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
     *
     * @return OrderAdjustment
     */
    public function createAdjustment($value)
    {
        return $this->adjustmentable->adjustments()->create(
            $this->generateAdjustment($value)
        );
    }

    /**
     * update adjustment.
     *
     * @param Adjustment $adjustment
     * @param mixed      $value
     *
     * @return Adjustment
     */
    public function updateAdjustment(Adjustment $adjustment, $value)
    {
        $adjustment->update(
            $this->generateAdjustment($value)
        );

        return $adjustment;
    }

    /**
     * generate adjustment.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function generateAdjustment($value)
    {
        $adjustmentTotal = $this->getAdjustmentTotal($this->adjustmentable, $value);

        return [
            'type' => $this->adjustmentType,
            'priority' => $this->priority,
            'adjustment_origin' => $this->adjustmentable->total,
            'adjustment_value' => $value,
            'adjustment_total' => $adjustmentTotal,
            'total' => $this->adjustmentable->total + $adjustmentTotal,
        ];
    }

    /**
     * should be deleted
     *
     * @param Adjustment $adjustment
     * @return bool
     */
    protected function shouldBeDeleted(Adjustment $adjustment)
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
     *
     * @return string
     */
    abstract protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value);
}
