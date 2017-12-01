<?php

namespace Denmasyarikin\Sales\Bank\Requests;

use Denmasyarikin\Sales\Bank\Bank;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateBankRequest extends CreateBankRequest
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
}
