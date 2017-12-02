<?php

namespace Denmasyarikin\Sales\Bank\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Bank\Bank;
use Denmasyarikin\Sales\Bank\Requests\CreateBankRequest;
use Denmasyarikin\Sales\Bank\Requests\UpdateBankRequest;
use Denmasyarikin\Sales\Bank\Requests\DeleteBankRequest;
use Denmasyarikin\Sales\Bank\Transformers\BankListTransformer;

class BankController extends Controller
{
    /**
     * bank list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $banks = $this->getBankList($request);

        $transform = new BankListTransformer($banks);

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * get bank list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getBankList(Request $request)
    {
        $banks = Bank::orderBy('created_at', 'DESC');

        if ($request->has('key')) {
            $banks->where('id', $request->key);
            $banks->orwhere('name', 'like', "%{$request->key}%");
            $banks->orWhere('account_name', 'like', "%{$request->key}%");
        }

        return $banks->get();
    }

    /**
     * create bank.
     *
     * @param CreateBankRequest $request
     *
     * @return json
     */
    public function createBank(CreateBankRequest $request)
    {
        Bank::create($request->only([
            'name', 'logo', 'account_name', 'account_number',
        ]));

        return new JsonResponse(['message' => 'Bank has been created'], 201);
    }

    /**
     * update bank.
     *
     * @param UpdateBankRequest $request
     *
     * @return json
     */
    public function updateBank(UpdateBankRequest $request)
    {
        $bank = $request->getBank();

        $bank->update($request->only([
            'name', 'logo', 'account_name', 'account_number',
        ]));

        return new JsonResponse(['message' => 'Bank has been updated']);
    }

    /**
     * delete bank.
     *
     * @param DeleteBankRequest $request
     *
     * @return json
     */
    public function deleteBank(DeleteBankRequest $request)
    {
        $bank = $request->getBank();
        $bank->delete();

        return new JsonResponse(['message' => 'Bank has been deleted']);
    }
}