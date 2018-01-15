<?php

namespace Denmasyarikin\Sales\_Tax\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\_Tax\Tax;
use Denmasyarikin\Sales\_Tax\Transformers\TaxListTransformer;

class TaxController extends Controller
{
    /**
     * tax list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $taxs = $this->getTaxList($request);

        $transform = new TaxListTransformer($taxs);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get tax list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getTaxList(Request $request)
    {
        $taxs = Tax::with('order', 'order.customer')->orderBy('created_at', 'DESC');

        $this->dateRange($taxs, $request);

        return $taxs->paginate($request->get('per_page') ?: 10);
    }
}
