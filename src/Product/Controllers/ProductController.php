<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductDetailTransformer;

class ProductController extends Controller
{
    /**
     * get list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $products = $this->getProductList($request);

        $transform = new ProductListTransformer($products);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get product list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getProductList(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC');

        if ($request->has('key')) {
            $products->where('id', $request->key);
            $products->orwhere('name', 'like', "%{$request->key}%");
            $products->orWhere('description', 'like', "%{$request->key}%");
        }

        return $products->paginate(20);
    }

    /**
     * get detail.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductRequest $request, $id)
    {
        $transform = new ProductDetailTransformer($request->getProduct());

        return new JsonResponse(['data' => $transform->toArray()]);
    }
}
