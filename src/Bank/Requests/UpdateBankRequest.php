<?php

namespace Denmasyarikin\Sales\Bank\Requests;

use Denmasyarikin\Sales\Bank\Bank;

class UpdateBankRequest extends DetailBankRequest
{
    /**
     * get bank.
     *
     * @return Bank
     */
    public function getBank(): ?Bank
    {
        $bank = parent::getBank();

        $this->checkFreshData($bank);

        return $bank;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'logo' => 'nullable',
            'account_number' => 'required|numeric',
            'account_name' => 'required|min:4|max:20',
        ];
    }
}
