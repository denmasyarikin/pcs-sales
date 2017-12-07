<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use Denmasyarikin\Sales\Order\Contracts\Adjustment;
use Denmasyarikin\Sales\Order\Contracts\Markupable;

class MarkupFactory
{
    /**
     * markupable
     *
     * @var Markupable
     */
    protected $markupable;

    /**
     * Create a new Constructor instance.
     *
     * @param Markupable $markupable
     * @return void
     */
    public function __construct(Markupable $markupable)
    {
        $this->markupable = $markupable;
    }

    /**
     * apply markup
     *
     * @param float $percent
     * @return void
     */
    public function applyMarkup(float $percent)
    {
        $markup = $this->markupable->getMarkup();

        if (is_null($markup)) {
            $markup = $this->createMarkup($this->markupable, $percent);
        } else {
            $this->markupable->adjustment_total += $markup->adjustment_total;
            $this->updateMarkup($markup, $percent);
        }

        $this->markupable->adjustment_total -= $markup->adjustment_total;
        $this->markupable->save();
        $this->markupable->updateTotal();
    }

    /**
     * create markup
     *
     * @param Markupable $markupable
     * @param float $percent
     * @return OrderAdjustment
     */
    protected function createMarkup(Markupable $markupable, float $percent)
    {
        return $markupable->adjustments()->create(
            $this->generateMarkup($percent, $markupable)
        );
    }

    /**
     * update markup
     *
     * @param Adjustment $adjustment
     * @param float $percent
     * @return void
     */
    protected function updateMarkup(Adjustment $adjustment, float $percent)
    {
        return $adjustment->update(
            $this->generateMarkup($percent, $adjustment->getAdjustmentable())
        );
    }

    /**
     * generate markup
     *
     * @param float $percent
     * @param Markupable $markupable
     * @return array
     */
    protected function generateMarkup(float $percent, Markupable $markupable)
    {
        $field = $markupable->getTotalFieldName();
        $markup = ceil(($percent * $markupable->{$field}) / 100);

        return [
            'type' => 'markup',
            $field => $markupable->{$field},
            'adjustment_value' => $percent,
            'adjustment_total' => $markup,
            'total' => $markupable->{$field} + $markup
        ];
    }
}
