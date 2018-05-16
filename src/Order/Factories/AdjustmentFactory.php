<?php

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Adjustmentable;
use Symfony\Component\Process\Exception\InvalidArgumentException;

abstract class AdjustmentFactory
{
    /**
     * sequence.
     *
     * @var int
     */
    protected $sequence;

    /**
     * adjustment type.
     *
     * @var string
     */
    protected $adjustmentType;

    /**
     * adjustment rule.
     *
     * @var string
     */
    protected $adjustmentRule;

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
     * @param mxied $rule
     * @param bool  $resetting
     *
     * @return Adjustmentable
     */
    public function apply($value, $rule = null, $resetting = false)
    {
        $this->setAdjustmentRule($rule);
        $adjustment = $this->getAdjustment($this->adjustmentable);

        if (is_null($adjustment)) {
            $adjustment = $this->createAdjustment($value);
        } else {
            if (!$resetting) {
                $this->reverseAdjustmentable($adjustment);
            }
            $adjustment = $this->updateAdjustment($adjustment, $value);
        }

        $this->updateAdjustmentable($adjustment);

        if (!$resetting and $this->isNeedResetAdjustments()) {
            static::resetAdjustments($this->adjustmentable);
        }

        // if value has been applyed and value not effected
        if ($this->shouldDelete($adjustment)) {
            $adjustment->delete();
        }

        return $this->adjustmentable;
    }

    /**
     * set adjusment rule.
     *
     * @param string $rule
     */
    protected function setAdjustmentRule($rule)
    {
        if (!is_null($rule)) {
            $this->adjustmentRule = $rule;
        }

        // if tax rule already define in factory
        // if voucher rule will be in voucher TODO
        // then just make a sure that rule is defined

        if (is_null($this->adjustmentRule)) {
            throw new InvalidArgumentException('Adjustment rule is not define');
        }
    }

    /**
     * check is need reset all adjustments.
     *
     * @return bool
     */
    protected function isNeedResetAdjustments()
    {
        return $this->adjustmentable->adjustments()->count() > 1;
    }

    /**
     * reverse adjustmentable to before apply.
     *
     * @param Adjustment $adjustment
     */
    protected function reverseAdjustmentable(Adjustment $adjustment)
    {
        $this->adjustmentable->adjustment_total -= $adjustment->adjustment_total;
        $this->adjustmentable->total -= $adjustment->adjustment_total;
        $this->adjustmentable->save();
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
     *
     * @param Adjustmentable $adjustmentable
     */
    public static function resetAdjustments(Adjustmentable $adjustmentable)
    {
        // first we need to reset adjusmentable
        $itemTotal = $adjustmentable->total - $adjustmentable->adjustment_total;
        $adjustmentable->update(['adjustment_total' => 0, 'total' => $itemTotal]);

        // then get all adjustment that has been ordering by sequence asc
        $adjustments = $adjustmentable->getAdjustments();

        // lets apply one by one
        foreach ($adjustments as $adjustment) {
            $factoryClass = 'Denmasyarikin\Sales\Order\Factories\\'.ucwords($adjustment->type).'Factory';

            if (!class_exists($factoryClass)) {
                // TODO resarch for this exception what should be
                throw new \Exception('Factory class not exists');
            }

            $factory = new $factoryClass($adjustmentable);
            $factory->apply($adjustment->adjustment_value, $adjustment->adjustment_rule, true);
        }
    }

    /**
     * create adjustment.
     *
     * @param mixed $value
     *
     * @return OrderAdjustment
     */
    protected function createAdjustment($value)
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
    protected function updateAdjustment(Adjustment $adjustment, $value)
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
            'sequence' => $this->sequence,
            'type' => $this->adjustmentType,
            'adjustment_rule' => $this->adjustmentRule,
            'adjustment_value' => $value,
            'before_adjustment' => $this->adjustmentable->total,
            'adjustment_total' => $adjustmentTotal,
            'after_adjustment' => $this->adjustmentable->total + $adjustmentTotal,
        ];
    }

    /**
     * should be deleted.
     *
     * @param Adjustment $adjustment
     *
     * @return bool
     */
    protected function shouldDelete(Adjustment $adjustment)
    {
        return empty($adjustment->adjustment_value);
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
     * @return int
     */
    abstract protected function getAdjustmentTotal(Adjustmentable $adjustmentable, $value);
}
