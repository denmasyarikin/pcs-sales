<?php

namespace Denmasyarikin\Sales\Order\Requests;

use App\Http\Requests\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'workspace_id' => 'required|exists:core_workspaces,id',
            'chanel_id' => 'required|exists:core_chanels,id',
            'items' => 'nullable|array',
            'items.*.type' => 'required|in:product,service,good,manual',
            'items.*.parent_id' => 'nullable|numeric|exists:sales_order_items,id',
            'items.*.reference_id' => 'nullable|numeric',
            'items.*.reference_type' => 'nullable|required_with:reference_id',
            'items.*.reference_configurations' => 'nullable',
            'items.*.name' => 'required|max:50',
            'items.*.specific' => 'nullable|max:50',
            'items.*.quantity' => 'required|integer',
            'items.*.unit_price' => 'required|numeric',
            'items.*.unit_total' => 'required|numeric',
            'items.*.note' => 'nullable',
            'items.*.unit_id' => 'required|exists:core_units,id',
            'items.*.markup' => 'nullable|numeric',
            'items.*.markup_rule' => 'nullable|in:fixed,percentage',
            'items.*.discount' => 'nullable|numeric',
            'items.*.discount_rule' => 'nullable|in:fixed,percentage',
            'items.*.voucher' => 'nullable|size:8|voucher',
            'ppn' => 'nullable|boolean',
            'discount' => 'nullable|array',
            'discount.value' => 'integer',
            'discount.rule' => 'in:fixed,percentage',
            'voucher' => 'nullable',
        ];
    }
}
