<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

class ProductProcessDetailTransformer extends Detail
{
    /**
     * get data.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        return [
            'id' => $model->id,
            'type' => $model->type,
            'type_as' => $model->type_as,
            'parent_id' => $model->parent_id,
            'reference_id' => $model->reference_id,
            'name' => $model->name,
            'specific' => $model->specific,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'quantity' => $model->quantity,
            'base_price' => $model->base_price,
            'required' => (bool) $model->required,
            'static_price' => (bool) $model->static_price,
            'static_to_order_count' => $model->static_to_order_count,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name', 'specific', 'formatted']))->toArray(),
            'children' => $this->getChildren($model->children),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * get children.
     *
     * @param Collection $children
     *
     * @return array
     */
    protected function getChildren(Collection $children)
    {
        $data = [];

        foreach ($children as $child) {
            $data[] = (new self($child))->toArray();
        }

        return $data;
    }
}
