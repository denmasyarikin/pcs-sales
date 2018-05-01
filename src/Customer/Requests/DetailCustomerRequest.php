<?php

namespace Denmasyarikin\Sales\Customer\Requests;

use Modules\Chanel\Chanel;
use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Customer\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailCustomerRequest extends FormRequest
{
    /**
     * customer.
     *
     * @var Customer
     */
    public $customer;

    /**
     * get customer.
     *
     * @return Customer
     */
    public function getCustomer(): ?Customer
    {
        if ($this->customer) {
            return $this->customer;
        }

        $customer = null;
        $id = (int) $this->route('id');

        if (Customer::isCode($id)) {
            $ids = Customer::getIdFromCode($id);
            $customer = Customer::whereHas('chanel', function($chanel) use ($ids) {
                $chanelIds = Chanel::getIdFromCode($ids['chanel_code']);
                $chanel->whereType($chanelIds['type']);
                $chanel->whereId($chanelIds['id']);
            })->find($ids['id']);
        } else {
            $customer = Customer::find($id);
        }

        if ($this->customer = $customer) {
            return $this->customer;
        }

        throw new NotFoundHttpException('Customer Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
