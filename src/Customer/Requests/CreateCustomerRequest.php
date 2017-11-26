<?php

namespace Denmasyarikin\Sales\Customer\Requests;

use App\Http\Requests\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:public,agent,company',
            'name' => 'required|max:50',
            'address' => '',
            'telephone' => 'min:4|max:20|numeric',
            'email' => 'email',
            'contact_person' => '',
            'user_id' => 'numeric|exists:core_users,id',
        ];
    }
}
