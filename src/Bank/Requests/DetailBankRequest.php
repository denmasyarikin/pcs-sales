<?php

namespace Denmasyarikin\Sales\Bank\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Bank\Bank;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailBankRequest extends FormRequest
{
    /**
     * bank.
     *
     * @var Bank
     */
    public $bank;

    /**
     * get bank.
     *
     * @return Bank
     */
    public function getBank(): ?Bank
    {
        if ($this->bank) {
            return $this->bank;
        }

        $id = $this->route('id');

        if ($this->bank = Bank::find($id)) {
            return $this->bank;
        }

        throw new NotFoundHttpException('Bank Not Found');
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
